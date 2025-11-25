<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class OrderController extends Controller
{
    // Fungsi helper format Rupiah
    private function formatRupiah($angka) {
        return 'Rp ' . number_format($angka ?? 0, 0, ',', '.');
    }

    // --- SUMBER DATA DUMMY ---
    private function getDummyOrders() {
        // Buat daftar order dummy yang lebih lengkap
        return [
            1001 => [
                'order_id' => 1001, 'user_id' => 1, 'total_price' => 785000, 'status' => 'pending', 'order_date' => now()->subHours(2)->toDateTimeString(),
                'nama_penerima' => 'Afa Pending', 'alamat_lengkap' => 'Jl. Pending No. 1', 'kota' => 'Bandung', 'provinsi' => 'Jabar', 'kode_pos' => '40200', 'no_telepon' => '08111', 'kurir' => 'Regular',
                'payment_method' => 'Bank Transfer', 'payment_detail' => 'BCA', 'transaction_code' => 'TRX-PNDG-111',
                'items' => [
                    ['id_produk' => 'P001', 'quantity' => 2, 'price_at_purchase' => 325000, 'nama_produk' => 'Oversized Denim Jacket', 'gambar_produk' => 'oro.png'],
                    ['id_produk' => 'P002', 'quantity' => 1, 'price_at_purchase' => 125000, 'nama_produk' => 'White T-Shirt', 'gambar_produk' => 't1.jpg'],
                ]
            ],
            1002 => [
                'order_id' => 1002, 'user_id' => 1, 'total_price' => 349000, 'status' => 'prepared', 'order_date' => now()->subDays(1)->toDateTimeString(),
                'nama_penerima' => 'Afa Prepared', 'alamat_lengkap' => 'Jl. Prepared No. 2', 'kota' => 'Bandung', 'provinsi' => 'Jabar', 'kode_pos' => '40201', 'no_telepon' => '08222', 'kurir' => 'Regular',
                'payment_method' => 'E-Wallet', 'payment_detail' => 'GoPay', 'transaction_code' => 'TRX-PREP-222',
                'items' => [
                    ['id_produk' => 'GLS001', 'quantity' => 1, 'price_at_purchase' => 349000, 'nama_produk' => 'Gia Jeans Highwaist', 'gambar_produk' => 't3.jpg'], // Pastikan gambar ini ada atau ganti
                ]
            ],
             1003 => [
                'order_id' => 1003, 'user_id' => 1, 'total_price' => 135000, 'status' => 'shipped', 'order_date' => now()->subDays(3)->toDateTimeString(),
                'nama_penerima' => 'Afa Shipped', 'alamat_lengkap' => 'Jl. Shipped No. 3', 'kota' => 'Jakarta', 'provinsi' => 'DKI', 'kode_pos' => '10110', 'no_telepon' => '08333', 'kurir' => 'Express',
                'payment_method' => 'COD', 'payment_detail' => null, 'transaction_code' => 'TRX-SHIP-333',
                'items' => [
                    ['id_produk' => 'P003', 'quantity' => 1, 'price_at_purchase' => 135000, 'nama_produk' => 'Basic Crop Top', 'gambar_produk' => 't4.jpg'], // Pastikan gambar ini ada
                ]
            ],
        ];
    }

    /**
     * Menampilkan daftar pesanan (My Orders).
     */
    public function listOrders(Request $request)
    {
        $status_filter = $request->query('status', 'all');
        $all_orders = $this->getDummyOrders();

        // Filter orders based on status
        $orders = [];
        if ($status_filter === 'all') {
            $orders = $all_orders;
        } else {
            foreach ($all_orders as $order) {
                if (isset($order['status']) && strtolower($order['status']) === strtolower($status_filter)) {
                    $orders[] = $order; // Langsung tambahkan jika status cocok
                }
            }
        }

        // Hitung jumlah per status (dummy)
        $order_counts = [
            'all' => count($all_orders), 'pending' => 0, 'prepared' => 0, 'shipped' => 0, 'completed' => 0, 'cancelled' => 0
        ];
        foreach ($all_orders as $order) {
            $status = strtolower($order['status'] ?? '');
            if (array_key_exists($status, $order_counts)) {
                $order_counts[$status]++;
            }
        }


        return view('orders.list', compact('orders', 'status_filter', 'order_counts'));
    }

    /**
     * Mengambil detail order untuk ditampilkan di modal (AJAX).
     */
    public function getOrderDetailsAjax($order_id)
    {
        $all_orders = $this->getDummyOrders(); // Ambil semua data dummy lagi

        // Cari order berdasarkan ID (pastikan ID adalah integer)
        $order_detail = $all_orders[(int)$order_id] ?? null;

        if (!$order_detail) {
            return response('<p style="color: red; text-align: center;">Order details not found.</p>', 404)
                   ->header('Content-Type', 'text/html'); // Pastikan header HTML
        }

        // Kirim data ke view partial modal
        return view('orders._details_modal_content', compact('order_detail'))->render();
    }
}