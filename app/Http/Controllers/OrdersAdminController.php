<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;
use Carbon\Carbon; 

class OrdersAdminController extends Controller
    {
    private function getDummyOrders()
    {
        return [
            [
                'order_id' => 48,
                'order_date' => '2025-06-16 12:57:24',
                'total_price' => 347000.00,
                'alamat_lengkap' => 'Alamat Pengguna Wholy, Jl. Sudirman No. 12, Jakarta',
                'metode_pembayaran' => 'Bank Transfer',
                'bank_name' => 'Permata',
                'ewallet_provider_name' => null,
                'status' => 'Pending',
                'username' => 'wholy',
                'nama_produk_list' => 'Shenning Knit<br>Executive Sleeve Stripes',
                'detail_produk' => [ 
                    ['nama' => 'Shenning Knit', 'qty' => 1, 'harga' => 127000, 'gambar_produk' => 'shenning.jpg', 'deskripsi' => 'Kain rajut premium, nyaman dipakai sehari-hari.'],
                    ['nama' => 'Executive Sleeve Stripes', 'qty' => 1, 'harga' => 220000, 'gambar_produk' => 'Sleeve Stripes.jpg', 'deskripsi' => 'Kemeja lengan panjang motif garis-garis, look profesional.'],
                ],
            ],
            [
                'order_id' => 44,
                'order_date' => '2025-06-11 15:59:19',
                'total_price' => 202000.00,
                'alamat_lengkap' => 'Alamat Pengguna Luuccy, Jl. Gajah Mada No. 5, Surabaya',
                'metode_pembayaran' => 'Bank Transfer',
                'bank_name' => 'Mandiri',
                'ewallet_provider_name' => null,
                'status' => 'Completed',
                'username' => 'luuccy_',
                'nama_produk_list' => 'Shenning Knit',
                'detail_produk' => [
                    ['nama' => 'Shenning Knit', 'qty' => 1, 'harga' => 202000, 'gambar_produk' => 'shenning.jpg', 'deskripsi' => 'Atasan dasar yang wajib dimiliki, bahan adem.'],
                ],
            ],
            [
                'order_id' => 43,
                'order_date' => '2025-06-11 13:58:34',
                'total_price' => 360000.00,
                'alamat_lengkap' => 'Alamat Pengguna Velliya, Jl. Diponegoro No. 10, Bandung',
                'metode_pembayaran' => 'Bank Transfer',
                'bank_name' => 'Mandiri',
                'ewallet_provider_name' => null,
                'status' => 'Completed',
                'username' => 'veliiya',
                'nama_produk_list' => 'Denim Overshirt',
                'detail_produk' => [
                    ['nama' => 'Denim Overshirt', 'qty' => 1, 'harga' => 360000, 'gambar_produk' => 'denim-over.jpg', 'deskripsi' => 'Outer denim kasual, cocok untuk gaya berlapis.'],
                ],
            ],
            [
                'order_id' => 42,
                'order_date' => '2025-06-10 15:22:32',
                'total_price' => 292500.00,
                'alamat_lengkap' => 'Alamat Pengguna Dior, Jl. Asia Afrika No. 3, Jakarta',
                'metode_pembayaran' => 'Bank Transfer',
                'bank_name' => 'Mandiri',
                'ewallet_provider_name' => null,
                'status' => 'Pending',
                'username' => 'dior',
                'nama_produk_list' => 'Sheen Pashmina Silk<br>Ribbonie Red Shirt',
                'detail_produk' => [
                    ['nama' => 'Sheen Pashmina Silk', 'qty' => 1, 'harga' => 72500, 'gambar_produk' => 'pashmina-silk.jpg', 'deskripsi' => 'Kerudung pashmina sutra kilau, mewah dan elegan.'],
                    ['nama' => 'Ribbonie Red Shirt', 'qty' => 1, 'harga' => 220000, 'gambar_produk' => 'ribbonie-red.jpg', 'deskripsi' => 'Kemeja merah dengan aksen pita, gaya feminim.'],
                ],
            ],
            [
                'order_id' => 32,
                'order_date' => '2025-06-10 09:13:05',
                'total_price' => 1799500.00,
                'alamat_lengkap' => 'Alamat Pengguna Carlotee, Jl. Gatot Subroto No. 7, Bogor',
                'metode_pembayaran' => 'Bank Transfer',
                'bank_name' => 'Mandiri',
                'ewallet_provider_name' => null,
                'status' => 'Prepared',
                'username' => 'carlotee_',
                'nama_produk_list' => 'Loose Pants<br>Sepatu Nike<br>Ladiesbag<br>Sheen Pashmina Silk<br>Hyunbin Kaos Polos<br>Grizzly Rompi<br>Cargo Loose Jeans<br>Gia Jeans Highwaist<br>Jeans Boyfriend<br>Executive Sleeve Stripes',
                'detail_produk' => [
                    ['nama' => 'Loose Pants', 'qty' => 1, 'harga' => 100000, 'gambar_produk' => 'loose-pants.jpg', 'deskripsi' => 'Celana longgar nyaman, gaya santai.'],
                    ['nama' => 'Sepatu Nike', 'qty' => 1, 'harga' => 250000, 'gambar_produk' => 'nike-sneaker.jpg', 'deskripsi' => 'Sepatu olahraga kasual, desain keren.'],
                    ['nama' => 'Ladiesbag', 'qty' => 1, 'harga' => 300000, 'gambar_produk' => 'ladies-bag.jpg', 'deskripsi' => 'Tas bahu kulit imitasi, mewah.'],
                    ['nama' => 'Sheen Pashmina Silk', 'qty' => 1, 'harga' => 72500, 'gambar_produk' => 'pashmina-silk.jpg', 'deskripsi' => 'Kerudung pashmina sutra kilau.'],
                    ['nama' => 'Hyunbin Kaos Polos', 'qty' => 1, 'harga' => 150000, 'gambar_produk' => 'hyunbin-kaos.jpg', 'deskripsi' => 'Kaos polos katun premium.'],
                    ['nama' => 'Grizzly Rompi', 'qty' => 1, 'harga' => 80000, 'gambar_produk' => 'grizzly-rompi.jpg', 'deskripsi' => 'Rompi hangat dengan motif bulu.'],
                    ['nama' => 'Cargo Loose Jeans', 'qty' => 1, 'harga' => 280000, 'gambar_produk' => 'cargo-jeans.jpg', 'deskripsi' => 'Jeans kargo longgar, gaya militer.'],
                    ['nama' => 'Gia Jeans Highwaist', 'qty' => 1, 'harga' => 120000, 'gambar_produk' => 'gia-jeans.jpg', 'deskripsi' => 'Jeans pinggang tinggi, membentuk siluet.'],
                    ['nama' => 'Jeans Boyfriend', 'qty' => 1, 'harga' => 119000, 'gambar_produk' => 'jeans-boyfriend.jpg', 'deskripsi' => 'Jeans gaya boyfriend, santai dan trendy.'],
                    ['nama' => 'Executive Sleeve Stripes', 'qty' => 1, 'harga' => 220000, 'gambar_produk' => 'executive-stripes.jpg', 'deskripsi' => 'Kemeja lengan panjang motif garis-garis.'],
                ],
            ],
            [
                'order_id' => 33,
                'order_date' => '2025-06-10 09:13:05',
                'total_price' => 1799500.00,
                'alamat_lengkap' => 'Alamat Pengguna Luuccy_Grizzly, Jl. Pahlawan No. 4, Semarang',
                'metode_pembayaran' => 'Bank Transfer',
                'bank_name' => 'Mandiri',
                'ewallet_provider_name' => null,
                'status' => 'Completed',
                'username' => 'luuccy_grizzly',
                'nama_produk_list' => 'Loose Pants, Sepatu Nike, Ladiesbag...',
                'detail_produk' => [['nama' => 'Loose Pants', 'qty' => 1, 'harga' => 100000, 'gambar_produk' => 'loose-pants.jpg', 'deskripsi' => 'Celana longgar nyaman.']],
            ],
            [
                'order_id' => 7,
                'order_date' => '2025-06-05 15:43:28',
                'total_price' => 239000.00,
                'alamat_lengkap' => 'Alamat Pengguna Luuccy, Jl. Gresik No. 7, Gresik',
                'metode_pembayaran' => 'E-Wallet',
                'bank_name' => null,
                'ewallet_provider_name' => 'Gopay',
                'status' => 'Pending',
                'username' => 'luuccy_',
                'nama_produk_list' => 'Kemeja Puff Chin<br>Vinty Top<br>Jeans Highwaist',
                'detail_produk' => [
                    ['nama' => 'Kemeja Puff Chin', 'qty' => 1, 'harga' => 100000, 'gambar_produk' => 'puff-chin.jpg', 'deskripsi' => 'Kemeja lengan puff gaya retro.'],
                    ['nama' => 'Vinty Top', 'qty' => 1, 'harga' => 60000, 'gambar_produk' => 'vinty-top.jpg', 'deskripsi' => 'Atasan V-neck kasual.'],
                    ['nama' => 'Jeans Highwaist', 'qty' => 1, 'harga' => 79000, 'gambar_produk' => 'highwaist-jeans.jpg', 'deskripsi' => 'Jeans pinggang tinggi.'],
                ],
            ],
            [
                'order_id' => 4,
                'order_date' => '2025-06-05 07:38:51',
                'total_price' => 90000.00,
                'alamat_lengkap' => 'Alamat Pengguna Luuccy, Jl. Mojokerto No. 10, Mojokerto',
                'metode_pembayaran' => 'E-Wallet',
                'bank_name' => null,
                'ewallet_provider_name' => 'Dana',
                'status' => 'Completed',
                'username' => 'luuccy_',
                'nama_produk_list' => 'Cardi Rajut Snowy',
                'detail_produk' => [['nama' => 'Cardi Rajut Snowy', 'qty' => 1, 'harga' => 90000, 'gambar_produk' => 'cardi-snowy.jpg', 'deskripsi' => 'Kardigan rajut tebal dan hangat.']],
            ],
        ];
    }

    private function findOrderById($orderId)
    {
        $allOrders = $this->getDummyOrders();
        foreach ($allOrders as $order) {
            if ($order['order_id'] == $orderId) {
                return $order;
            }
        }
        return null;
    }

    public function index()
    {
        $orders = $this->getDummyOrders();

        usort($orders, function ($a, $b) {
            return strtotime($b['order_date']) - strtotime($a['order_date']);
        });

        return view('ordersAdmin.ordersAdmin', compact('orders'));
    }

    public function show($order_id)
    {
        $order = $this->findOrderById($order_id);

        if (!$order) {
            abort(404, "Order ID #{$order_id} tidak ditemukan.");
        }
        
        $order['nama_penerima'] = 'Penerima ' . $order['username'];
        $order['no_telepon'] = '0812' . str_pad($order['order_id'], 4, '0', STR_PAD_LEFT);
        $order['email'] = $order['username'] . '@contoh.com';
        $order['kota'] = explode(', ', $order['alamat_lengkap'])[1] ?? 'Kota Dummy';
        $order['provinsi'] = 'Provinsi Dummy';
        $order['kode_pos'] = '12345';
        $order['kurir'] = 'JNE Reguler';
        
        $sub_total_calculated = 0;
        foreach ($order['detail_produk'] as $item) {
             $sub_total_calculated += ($item['harga'] * $item['qty']);
        }
        
        $order_details = $order;
        $order_itemss_data = $order['detail_produk'];
        $sub_total_calculated = $sub_total_calculated;
        $discount_amount = 0.00;
        $shipping_charge = 25000.00;
        $estimated_tax = 0.00;


        return View::make('ordersAdmin.orderDetail', compact(
            'order_details', 
            'order_itemss_data', 
            'sub_total_calculated', 
            'discount_amount', 
            'shipping_charge', 
            'estimated_tax'
        ));
    }

    public function updateStatus(Request $request)
    {
        $orderId = $request->input('order_id');
        $newStatus = $request->input('status');

        $validStatuses = ['pending', 'prepared', 'shipped', 'completed'];

        if (!$orderId || !in_array($newStatus, $validStatuses)) {
            return Response::json(['success' => false, 'message' => 'ID order atau status tidak valid.'], 400);
        }

        return Response::json([
            'success' => true,
            'message' => "Status order #{$orderId} berhasil diperbarui menjadi " . ucfirst($newStatus) . " (Simulasi)."
        ]);
    }
}