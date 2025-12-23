<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use Carbon\Carbon;

class ApiPaymentController extends Controller
{
    private function formatRupiah($angka) {
        return 'Rp ' . number_format($angka ?? 0, 0, ',', '.');
    }

    public function showPaymentDetails(Request $request)
    {
        $userId = Auth::id();

        if (!$userId) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        // 1. Get User's Order
        if ($request->has('order_id')) {
            $order = Order::where('user_id', $userId)
                          ->where('order_id', $request->order_id)
                          ->latest()
                          ->first();
        } else {
            $order = Order::where('user_id', $userId)
                          ->latest()
                          ->first();
        }

        if (!$order) {
            return response()->json([
                'status' => 'error',
                'message' => 'No order found.'
            ], 404);
        }

        // 2. Get Payment Data
        $payment = $order->payment;

        if (!$payment) {
            return response()->json([
                'status' => 'error',
                'message' => 'Payment data not found for this order.'
            ], 404);
        }

        // 3. Prepare Data
        $order_id = $order->order_id;
        $payment_code = $payment->transaction_code;
        $payment_expiration_time = Carbon::parse($order->order_date)->addHours(24);
        $total_amount_display = $this->formatRupiah($order->grand_total);

        // Payment Method Logic
        $payment_method_display = 'Unknown';
        $bank_choice_display = '';
        $ewallet_provider_display = '';

        if ($payment->method) {
            $payment_method_display = $payment->method->method_name;
        }

        if ($payment_method_display === 'Bank Transfer') {
            $bank_choice_display = $payment->bankDetail ? $payment->bankDetail->bank_name : 'Bank';
        } elseif ($payment_method_display === 'E-Wallet') {
            $ewallet_provider_display = $payment->ewalletDetail ? $payment->ewalletDetail->ewallet_provider_name : 'E-Wallet';
        }

        // 4. Instructions
        $payment_instructions = [];

        if ($payment_method_display === 'Bank Transfer') {
            $no_rek = $payment->bankDetail ? $payment->bankDetail->bank_account_number : '-';
            $an     = $payment->bankDetail ? $payment->bankDetail->account_holder_name : 'LOOKSEE Official';
            
            $payment_instructions = [
                'title' => $bank_choice_display . " Transfer",
                'details' => [
                    'bank_name' => $bank_choice_display,
                    'account_number' => $no_rek,
                    'account_holder' => $an,
                    'amount' => $total_amount_display
                ],
                'steps' => [
                    "Transfer ke rekening $no_rek a/n $an",
                    "Simpan bukti transfer",
                    "Pembayaran dicek otomatis"
                ]
            ];
        } elseif ($payment_method_display === 'E-Wallet') {
            $no_hp = $payment->ewalletDetail ? $payment->ewalletDetail->account_number : '-';
            $an    = $payment->ewalletDetail ? $payment->ewalletDetail->account_name : 'LOOKSEE Official';

            $payment_instructions = [
                'title' => $ewallet_provider_display,
                'details' => [
                    'provider' => $ewallet_provider_display,
                    'phone_number' => $no_hp,
                    'account_name' => $an,
                    'amount' => $total_amount_display
                ],
                'steps' => [
                    "Buka aplikasi $ewallet_provider_display",
                    "Transfer ke $no_hp ($an)",
                    "Masukkan jumlah $total_amount_display"
                ]
            ];
        } elseif ($payment_method_display === 'COD') {
            $payment_instructions = [
                'title' => "Cash on Delivery",
                'steps' => [
                    "Siapkan uang pas: $total_amount_display",
                    "Bayar ke kurir saat barang tiba"
                ]
            ];
        }

        $data = [
            'order_id' => $order_id,
            'payment_code' => $payment_code,
            'expiration_time' => $payment_expiration_time,
            'total_amount' => $order->grand_total,
            'formatted_amount' => $total_amount_display,
            'payment_method' => $payment_method_display,
            'bank_info' => $bank_choice_display ?: null,
            'ewallet_info' => $ewallet_provider_display ?: null,
            'instructions' => $payment_instructions
        ];

        return response()->json([
            'status' => 'success',
            'data' => $data
        ], 200);
    }
}