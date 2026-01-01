<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\BankTransferDetail;
use App\Models\EwalletTransferDetail;
use App\Models\OrderPayment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ApiPaymentController extends Controller
{
    public function showPaymentDetails(Request $request)
    {
        $orderId = $request->query('order_id');
        if (! $orderId) {
            return response()->json(['success' => false, 'message' => 'order_id is required'], 400);
        }

        $user = $request->user();
        $order = Order::where('order_id', $orderId)->where('user_id', $user->user_id)->with('payment')->first();
        if (! $order) {
            return response()->json(['success' => false, 'message' => 'Order not found'], 404);
        }

        $payment = $order->payment;
        if (! $payment) {
            // no payment record yet — return available top-level methods and providers
            $methods = PaymentMethod::all();
            $banks = BankTransferDetail::all();
            $ewallets = EwalletTransferDetail::all();
            return response()->json([
                'success' => true,
                'order' => $order,
                'payment_methods' => $methods,
                'bank_details' => $banks,
                'ewallet_details' => $ewallets,
            ]);
        }

        // Build user-facing instructions
        $instruction = '';
        $details = [];

        $method = $payment->method()->first();
        $methodName = strtolower($method->method_name ?? '');

        if ($methodName === 'cod' || str_contains($methodName, 'cod')) {
            $instruction = 'Pilih Cash on Delivery. Bayar saat barang diterima.';
            $details = ['type' => 'cod'];
        } elseif ($payment->e_wallet_payment_id_fk) {
            $ew = EwalletTransferDetail::where('e_wallet_payment_id', $payment->e_wallet_payment_id_fk)->first();
            $instruction = 'Selesaikan pembayaran melalui aplikasi e-wallet Anda atau buka payment URL.';
            $details = [
                'type' => 'ewallet',
                'provider' => $ew?->ewallet_provider_name,
                'phone_number' => $ew?->phone_number,
                'payment_url' => url("/api/payment/ewallet/{$payment->payment_id}/pay"),
                'transaction_code' => $payment->transaction_code,
            ];
        } elseif ($payment->bank_payment_id_fk) {
            $bank = BankTransferDetail::where('bank_payment_id', $payment->bank_payment_id_fk)->first();
            $va = $payment->transaction_code ?? $bank?->account_number;
            $instruction = "Transfer sejumlah Rp{number_format($payment->amount,0,',','.')} ke Virtual Account berikut: $va. Setelah transfer, lakukan konfirmasi pembayaran atau tunggu notifikasi.";
            $details = [
                'type' => 'bank_transfer',
                'bank_name' => $bank?->bank_name,
                'account_number' => $va,
                'account_holder' => $bank?->account_holder_name,
                'virtual_account' => $va,
                'transaction_code' => $payment->transaction_code,
            ];
        } else {
            // fallback: determine by method name
            if (str_contains($methodName, 'ewallet')) {
                $instruction = 'Selesaikan pembayaran melalui e-wallet yang dipilih.';
                $details = ['type' => 'ewallet', 'payment_url' => url("/api/payment/ewallet/{$payment->payment_id}/pay"), 'transaction_code' => $payment->transaction_code];
            } else {
                $instruction = 'Ikuti instruksi pembayaran yang tersedia.';
                $details = ['type' => 'unknown', 'transaction_code' => $payment->transaction_code];
            }
        }

        return response()->json([
            'success' => true,
            'order' => $order,
            'payment' => $payment,
            'instruction_text' => $instruction,
            'details' => $details,
        ]);
    }

    // Simulate e-wallet payment completion (user clicks pay in ewallet app)
    public function ewalletPay(Request $request, $paymentId)
    {
        $user = $request->user();
        $payment = OrderPayment::where('payment_id', $paymentId)->with('order')->first();
        if (! $payment || $payment->order->user_id !== $user->user_id) {
            return response()->json(['success' => false, 'message' => 'Payment not found'], 404);
        }
        if ($payment->transaction_status === 'success') {
            return response()->json(['success' => true, 'message' => 'Already paid']);
        }

        $xenditKey = env('XENDIT_SECRET_KEY');
        if ($xenditKey && !empty($payment->transaction_code)) {
            try {
                // Try to fetch ewallet charge status from Xendit
                $resp = Http::withBasicAuth($xenditKey, '')->get('https://api.xendit.co/v2/ewallets/' . $payment->transaction_code);
                if ($resp->successful()) {
                    $body = $resp->json();
                    if (($body['status'] ?? '') === 'COMPLETED' || ($body['status'] ?? '') === 'SUCCEEDED') {
                        $payment->transaction_status = 'success';
                        $payment->payment_date = Carbon::now();
                        $payment->save();
                        $order = $payment->order;
                        $order->status = 'paid';
                        $order->save();
                        return response()->json(['success' => true, 'message' => 'Payment successful', 'order' => $order, 'external' => $body]);
                    }
                }
            } catch (\Exception $e) {
                Log::error('Xendit ewallet verify error: ' . $e->getMessage());
            }
        }

        // fallback: mark as paid (simulation)
        $payment->transaction_status = 'success';
        $payment->payment_date = Carbon::now();
        if (empty($payment->transaction_code)) {
            $payment->transaction_code = strtoupper(bin2hex(random_bytes(5)));
        }
        $payment->save();

        // update order status
        $order = $payment->order;
        $order->status = 'paid';
        $order->save();

        return response()->json(['success' => true, 'message' => 'Payment marked as successful (simulated)', 'order' => $order]);
    }

    // Simulate bank transfer confirmation (user uploads proof / confirms transfer)
    public function bankConfirm(Request $request, $paymentId)
    {
        $user = $request->user();
        $payment = OrderPayment::where('payment_id', $paymentId)->with('order')->first();
        if (! $payment || $payment->order->user_id !== $user->user_id) {
            return response()->json(['success' => false, 'message' => 'Payment not found'], 404);
        }
        if ($payment->transaction_status === 'success') {
            return response()->json(['success' => true, 'message' => 'Already confirmed']);
        }

        $xenditKey = env('XENDIT_SECRET_KEY');
        if ($xenditKey && ! empty($payment->transaction_code)) {
            try {
                // Check virtual account payments via Xendit (search by account number)
                $resp = Http::withBasicAuth($xenditKey, '')->get('https://api.xendit.co/v2/virtual_accounts?account_number=' . $payment->transaction_code);
                if ($resp->successful()) {
                    $body = $resp->json();
                    // If Xendit returns a successful status, mark paid
                    // (This is an example; actual field may differ per provider)
                    if (!empty($body)) {
                        $payment->transaction_status = 'success';
                        $payment->payment_date = Carbon::now();
                        $payment->save();
                        $order = $payment->order;
                        $order->status = 'paid';
                        $order->save();
                        return response()->json(['success' => true, 'message' => 'Bank transfer confirmed via provider', 'order' => $order, 'external' => $body]);
                    }
                }
            } catch (\Exception $e) {
                Log::error('Xendit VA verify error: ' . $e->getMessage());
            }
        }

        // Fallback: accept user confirmation and mark paid
        $payment->transaction_status = 'success';
        $payment->payment_date = Carbon::now();
        $payment->save();

        $order = $payment->order;
        $order->status = 'paid';
        $order->save();

        return response()->json(['success' => true, 'message' => 'Bank transfer confirmed (simulated)', 'order' => $order]);
    }
}
