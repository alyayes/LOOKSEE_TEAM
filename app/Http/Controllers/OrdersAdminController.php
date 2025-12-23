<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OrdersAdminController extends Controller
{
    // Tampilkan dashboard orders
    public function index()
    {
        $orders = Order::with(['user', 'items.produk', 'payment.method', 'payment.bankDetail', 'payment.ewalletDetail'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($order) {
                // ... (Logic mapping sama seperti sebelumnya) ...
                $productNames = $order->items->map(function($item) {
                    return $item->produk ? $item->produk->nama_produk : 'Produk Dihapus';
                })->join(', ');

                $paymentMethod = '-';
                $bankName = null;
                $ewalletName = null;

                if ($order->payment) {
                    if ($order->payment->method) $paymentMethod = $order->payment->method->method_name;
                    if ($order->payment->bankDetail) $bankName = $order->payment->bankDetail->bank_name;
                    if ($order->payment->ewalletDetail) $ewalletName = $order->payment->ewalletDetail->ewallet_provider_name;
                }

                return [
                    'order_id' => $order->order_id,
                    'order_date' => $order->created_at ? $order->created_at->format('Y-m-d H:i:s') : '-',
                    'total_price' => $order->grand_total,
                    'metode_pembayaran' => $paymentMethod,
                    'status' => $order->status,
                    'username' => $order->user ? $order->user->username : 'Guest',
                    'nama_produk_list' => $productNames ?: '-',
                    'bank_name' => $bankName,
                    'ewallet_provider_name' => $ewalletName,
                ];
            });

        return view('admin.ordersAdmin.ordersAdmin', compact('orders'));
    }

    // Update status order via AJAX
    public function updateStatus(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,order_id',
            'status' => 'required|in:pending,prepared,shipped,completed,cancelled'
        ]);

        try {
            $order = Order::where('order_id', $request->order_id)->first();
            $order->status = $request->status;
            $order->save();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // Show detail order (INI YANG DIPERBAIKI AGAR ITEM MUNCUL)
    public function show($order_id)
    {
        // 1. Ambil Order beserta relasinya
        $order = Order::with(['user', 'items.produk', 'payment.method', 'payment.bankDetail', 'payment.ewalletDetail'])
                    ->findOrFail($order_id);

        // 2. Siapkan array $order_details (Sesuai yang diminta View)
        $paymentMethod = $order->payment && $order->payment->method ? $order->payment->method->method_name : '-';
        
        $order_details = [
            'order_id' => $order->order_id,
            'order_date' => $order->created_at,
            'status' => $order->status,
            'username' => $order->user ? $order->user->username : 'Guest',
            'email' => $order->user ? $order->user->email : '-',
            'metode_pembayaran' => $paymentMethod,
            'kurir' => $order->shipping_method,
            'total_price' => $order->grand_total,
            
            // Info Penerima
            'nama_penerima' => $order->nama_penerima,
            'no_telepon' => $order->no_telepon,
            'alamat_lengkap' => $order->alamat_lengkap,
            'kota' => $order->kota,
            'provinsi' => $order->provinsi,
            'kode_pos' => $order->kode_pos,

            // Info Payment Detail
            'bank_name' => $order->payment && $order->payment->bankDetail ? $order->payment->bankDetail->bank_name : '-',
            'bank_account_number' => $order->payment && $order->payment->bankDetail ? $order->payment->bankDetail->bank_account_number : '-',
            'bank_account_holder_name' => $order->payment && $order->payment->bankDetail ? $order->payment->bankDetail->account_holder_name : '-',
            
            'ewallet_provider_name' => $order->payment && $order->payment->ewalletDetail ? $order->payment->ewalletDetail->ewallet_provider_name : '-',
            'ewallet_phone_number' => $order->payment && $order->payment->ewalletDetail ? $order->payment->ewalletDetail->account_number : '-',
            'e_wallet_account_id' => $order->payment && $order->payment->ewalletDetail ? $order->payment->ewalletDetail->account_name : '-', // Asumsi field nama akun
        ];

        // 3. Siapkan array $order_itemss_data (Looping items)
        $order_itemss_data = $order->items->map(function($item) {
            return [
                'nama' => $item->produk ? $item->produk->nama_produk : 'Produk Dihapus',
                'gambar_produk' => $item->produk ? $item->produk->gambar_produk : null, // Nama file gambar
                'deskripsi' => $item->produk ? $item->produk->deskripsi : '',
                'harga' => $item->price_at_purchase, // Harga saat beli
                'qty' => $item->quantity,
            ];
        })->toArray();

        // 4. Hitung ringkasan
        $sub_total_calculated = 0;
        foreach($order_itemss_data as $i) {
            $sub_total_calculated += ($i['harga'] * $i['qty']);
        }
        
        $shipping_charge = $order->shipping_cost ?? 15000; // Default atau ambil dari DB
        $discount_amount = 0; // Logic diskon jika ada
        $estimated_tax = 0; // Logic pajak jika ada

        return view('admin.ordersAdmin.orderDetail', compact(
            'order_details', 
            'order_itemss_data', 
            'sub_total_calculated', 
            'shipping_charge', 
            'discount_amount', 
            'estimated_tax'
        ));
    }
}