<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Order;
use App\Models\OrderPayment;

class PaymentController extends Controller {
    
    private $userId = 33; // ID Dummy

    private function formatRupiah($angka) {
        return 'Rp ' . number_format($angka ?? 0, 0, ',', '.');
    }

    public function showPaymentDetails(Request $request)
    {
        // 1. Ambil Order Terakhir milik User ini
        // (Atau ambil spesifik dari URL ?order_id=5 jika ada)
        if ($request->has('order_id')) {
            $order = Order::where('user_id', $this->userId)
                          ->where('order_id', $request->order_id)
                          ->latest()
                          ->first();
        } else {
            $order = Order::where('user_id', $this->userId)
                          ->latest() // Ambil yang paling baru dibuat
                          ->first();
        }

        // Jika tidak ada order sama sekali
        if (!$order) {
            return redirect()->route('homepage')->with('error', 'Belum ada tagihan pembayaran.');
        }

        // 2. Ambil Data Pembayaran dari Relasi
        $payment = $order->payment; // Mengambil dari relasi hasOne di Model Order

        if (!$payment) {
            // Jika data pembayaran belum terbuat (kasus aneh, tapi jaga-jaga)
            return redirect()->route('orders.list')->with('error', 'Data pembayaran tidak ditemukan.');
        }

        // 3. Siapkan Data untuk View
        $order_id = $order->order_id;
        $payment_code = $payment->transaction_code;
        $payment_expiration_time = Carbon::parse($order->order_date)->addHours(24);
        
        $total_amount_display = $this->formatRupiah($order->grand_total);

        // Tentukan Jenis Metode Pembayaran (Bank / E-Wallet / COD)
        $payment_method_display = 'Unknown';
        $bank_choice_display = '';
        $ewallet_provider_display = '';

        if ($payment->method) {
            $payment_method_display = $payment->method->method_name;
        }

        // Ambil Detail Bank/E-Wallet
        if ($payment_method_display === 'Bank Transfer') {
            $bank_choice_display = $payment->bankDetail ? $payment->bankDetail->bank_name : 'Bank';
        } elseif ($payment_method_display === 'E-Wallet') {
            $ewallet_provider_display = $payment->ewalletDetail ? $payment->ewalletDetail->ewallet_provider_name : 'E-Wallet';
        }

        // 4. Siapkan Instruksi Pembayaran
        $payment_instructions = [ 'title' => 'Payment Method', 'steps' => ['No instructions available.'] ];

        if ($payment_method_display === 'Bank Transfer') {
            $no_rek = $payment->bankDetail ? $payment->bankDetail->bank_account_number : '1234567890';
            $an     = $payment->bankDetail ? $payment->bankDetail->account_holder_name : 'LOOKSEE Official';
            
            $payment_instructions['title'] = $bank_choice_display . " Transfer";
            $payment_instructions['steps'] = [
                "1. Transfer ke rekening:",
                "&emsp;Bank: " . $bank_choice_display,
                "&emsp;No. Rek: " . $no_rek,
                "&emsp;A/N: " . $an,
                "&emsp;Jumlah: " . $total_amount_display,
                "2. Simpan bukti transfer.",
                "3. Pembayaran akan dicek otomatis.",
            ];
        } elseif ($payment_method_display === 'E-Wallet') {
            $no_hp = $payment->ewalletDetail ? $payment->ewalletDetail->account_number : '081234567890'; // Asumsi kolom account_number ada di tabel ewallet
            $an    = $payment->ewalletDetail ? $payment->ewalletDetail->account_name : 'LOOKSEE Official';

            $payment_instructions['title'] = $ewallet_provider_display;
             $payment_instructions['steps'] = [
                "1. Buka aplikasi " . $ewallet_provider_display,
                "2. Pilih 'Bayar' atau 'Transfer'.",
                "3. Masukkan nomor tujuan: " . $no_hp . " (" . $an . ")",
                "4. Masukkan jumlah: " . $total_amount_display,
                "5. Selesaikan pembayaran.",
            ];
        } elseif ($payment_method_display === 'COD') {
             $payment_instructions['title'] = "Cash on Delivery";
             $payment_instructions['steps'] = [
                 "1. Siapkan uang pas: " . $total_amount_display,
                 "2. Serahkan pembayaran kepada kurir saat barang tiba.",
                 "3. Pesanan sedang disiapkan.",
             ];
        }

        return view('payment.details', compact(
            'order_id',
            'payment_code',
            'payment_expiration_time',
            'payment_method_display',
            'bank_choice_display',
            'ewallet_provider_display',
            'total_amount_display',
            'payment_instructions'
        ));
    }
}