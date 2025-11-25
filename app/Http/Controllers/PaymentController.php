<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class PaymentController extends Controller {
    
    private function formatRupiah($angka) {
        return 'Rp ' . number_format($angka ?? 0, 0, ',', '.');
    }

    public function showPaymentDetails()
    {
        
        $dummy_order = session('latest_order_details');
        $order_id = $dummy_order['order_id'] ?? rand(1000, 9999); 

        if (!$dummy_order) {
            $dummy_order = [
                'order_id' => $order_id,
                'total_price' => 785000,
                'status' => 'pending',
                'order_date' => now()->subMinutes(10)->toDateTimeString(), 
                'transaction_code' => 'TRX-LOOKSEE-' . strtoupper(uniqid()),
                'metode_pembayaran_supertype' => 'Bank Transfer', 
                'bank_pilihan_detail' => 'BCA',
                'bank_account_number' => '1234567890',
                'bank_account_holder' => 'LOOKSEE Store',
                'ewallet_provider_detail' => null,
                'ewallet_phone_number' => null,
                'ewallet_account_id' => null,
                'nama_penerima' => 'Afa',
                'alamat_lengkap' => 'Jl. Telekomunikasi No. 1, Bandung',
                'items' => [
                    ['nama_produk' => 'Oversized Denim Jacket', 'quantity' => 2, 'price_at_purchase' => 325000],
                    ['nama_produk' => 'White T-Shirt', 'quantity' => 1, 'price_at_purchase' => 125000],
                ]
            ];
             session(['latest_order_details' => $dummy_order]); 
                } else { 
            $dummy_order['order_date'] = $dummy_order['order_date'] ?? now()->subMinutes(5)->toDateTimeString();

            $dummy_order['transaction_code'] = $dummy_order['transaction_code'] ?? 'TRX-LOOKSEE-' . strtoupper(uniqid());

            $dummy_order['metode_pembayaran_supertype'] = $dummy_order['payment_method'] ?? 'Bank Transfer';

            $isBankTransfer = isset($dummy_order['payment_method']) && $dummy_order['payment_method'] == 'Bank Transfer';
            $dummy_order['bank_pilihan_detail'] = $isBankTransfer ? ('Selected Bank ID: ' . ($dummy_order['bank_choice'] ?? 'N/A')) : null; // Ubah teks agar jelas

            $isEWallet = isset($dummy_order['payment_method']) && $dummy_order['payment_method'] == 'E-Wallet';
            $dummy_order['ewallet_provider_detail'] = $isEWallet ? ('Selected E-Wallet ID: ' . ($dummy_order['ewallet_choice'] ?? 'N/A')) : null; // Ubah teks

            $dummy_order['bank_account_number'] = $dummy_order['bank_account_number'] ?? '1122334455';
            $dummy_order['bank_account_holder'] = $dummy_order['bank_account_holder'] ?? 'LOOKSEE Dummy';
            $dummy_order['ewallet_phone_number'] = $dummy_order['ewallet_phone_number'] ?? '089988776655';

            $dummy_order['total_price'] = $dummy_order['grand_total'] ?? ($dummy_order['total_price'] ?? 0);
        }


        $payment_code = $dummy_order['transaction_code'];
        $payment_expiration_time = Carbon::parse($dummy_order['order_date'])->addHours(24);
        $payment_method_display = $dummy_order['metode_pembayaran_supertype'];
        $bank_choice_display = $dummy_order['bank_pilihan_detail'] ?? '';
        $ewallet_provider_display = $dummy_order['ewallet_provider_detail'] ?? '';
        $total_amount_display = $this->formatRupiah($dummy_order['total_price'] ?? $dummy_order['grand_total'] ?? 0);

        $payment_instructions = [ 'title' => 'Payment Method', 'steps' => ['No instructions available.'] ];
        if ($payment_method_display === 'Bank Transfer') {
            $payment_instructions['title'] = $bank_choice_display . " Transfer";
            $payment_instructions['steps'] = [
                "1. Transfer ke rekening:",
                "&emsp;Bank: " . ($bank_choice_display ?: 'N/A'),
                "&emsp;No. Rek: " . ($dummy_order['bank_account_number'] ?? 'N/A'),
                "&emsp;A/N: " . ($dummy_order['bank_account_holder'] ?? 'N/A'),
                "&emsp;Jumlah: " . $total_amount_display,
                "2. Simpan bukti transfer.",
                "3. Pembayaran akan dicek otomatis.",
            ];
        } elseif ($payment_method_display === 'E-Wallet') {
            $payment_instructions['title'] = $ewallet_provider_display;
             $payment_instructions['steps'] = [
                "1. Buka aplikasi " . ($ewallet_provider_display ?: 'E-Wallet Anda'),
                "2. Pilih 'Bayar' atau 'Transfer'.",
                "3. Masukkan nomor tujuan: " . ($dummy_order['ewallet_phone_number'] ?? 'N/A'),
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