<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Order;
use App\Models\OrderPayment;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller {
    
    private function formatRupiah($angka) {
        return 'Rp ' . number_format($angka ?? 0, 0, ',', '.');
    }

    public function showPaymentDetails(Request $request)
    {
        $userId = Auth::id();

        // 1. AMBIL DATA ORDER
        if ($request->has('order_id')) {
            $order = Order::where('user_id', $userId)
                          ->where('order_id', $request->order_id)
                          ->latest()
                          ->first();
        } else {
            $order = Order::where('user_id', $userId)->latest()->first();
        }

        if (!$order) return redirect()->route('home')->with('error', 'Tagihan tidak ditemukan.');

        // 2. AMBIL PAYMENT
        $payment = $order->payment; 
        if (!$payment) return redirect()->route('orders.list')->with('error', 'Data pembayaran hilang.');

        // 3. SIAPKAN VARIABEL TAMPILAN
        $order_id = $order->order_id;
        $payment_code = $payment->transaction_code;
        $payment_expiration_time = Carbon::parse($order->order_date)->addHours(24);
        $total_amount_display = $this->formatRupiah($order->grand_total);

        // Nama Metode (Buat Judul)
        $payment_method_display = $payment->method ? $payment->method->method_name : 'Unknown';
        
        $bank_choice_display = '';
        $ewallet_provider_display = '';

        // Ambil Nama Provider/Bank untuk Display Header
        if ($payment->method_id == 3) { // Bank Transfer
            $bank_choice_display = $payment->bankDetail ? $payment->bankDetail->bank_name : 'Bank';
        } elseif ($payment->method_id == 2) { // E-Wallet
            $ewallet_provider_display = $payment->ewalletDetail ? $payment->ewalletDetail->ewallet_provider_name : 'E-Wallet';
        }

        // 4. LOGIC INSTRUKSI
        $payment_instructions = [ 'title' => 'Payment Method', 'steps' => ['No instructions available.'] ];

        if ($payment->method_id == 3) { 
            // --- BANK TRANSFER ---
            $no_rek = $payment->bankDetail ? $payment->bankDetail->account_number : '-';
            $an     = $payment->bankDetail ? $payment->bankDetail->account_holder_name : 'LOOKSEE Official';
            
            $payment_instructions['title'] = "Transfer via " . $bank_choice_display;
            $payment_instructions['steps'] = [
                "Masuk ke Mobile Banking atau ATM <strong>" . $bank_choice_display . "</strong>.",
                "Pilih menu <strong>Transfer</strong>.",
                "Masukkan Nomor Rekening / VA: <br><strong>" . ($payment_code ?? $no_rek) . "</strong>",
                "Pastikan nama penerima: <strong>" . $an . "</strong>.",
                "Masukkan jumlah bayar: <strong>" . $total_amount_display . "</strong>.",
                "Simpan bukti transfer."
            ];

        } elseif ($payment->method_id == 2) { 
            $no_hp = $payment->ewalletDetail ? $payment->ewalletDetail->account_number : '-'; 
            $an    = $payment->ewalletDetail ? $payment->ewalletDetail->account_name : 'LOOKSEE Official';

            $provName = $ewallet_provider_display ?: 'E-Wallet';

            $payment_instructions['title'] = "Bayar via " . $provName;
            $payment_instructions['steps'] = [
                "Buka aplikasi <strong>" . $provName . "</strong> di HP Anda.",
                "Pilih menu <strong>Bayar / Transfer</strong>.",
                "Masukkan nomor tujuan / kode bayar di atas.",
                "Pastikan nominal pembayaran sesuai: <strong>" . $total_amount_display . "</strong>.",
                "Konfirmasi pembayaran dan simpan bukti transaksi."
            ];

        } elseif ($payment->method_id == 1) { 
            $payment_instructions['title'] = "Cash on Delivery (COD)";
            $payment_instructions['steps'] = [
                "Siapkan uang pas sebesar: <strong>" . $total_amount_display . "</strong>.",
                "Tunggu kurir mengantarkan paket ke alamat Anda.",
                "Berikan uang kepada kurir saat barang diterima.",
            ];
        }

        return view('payment.details', compact(
            'order', 'payment',
            'order_id', 'payment_code', 'payment_expiration_time',
            'payment_method_display', 'bank_choice_display', 'ewallet_provider_display',
            'total_amount_display', 'payment_instructions'
        ));
    }
}