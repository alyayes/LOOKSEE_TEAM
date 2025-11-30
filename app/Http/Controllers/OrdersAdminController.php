<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrdersAdminController extends Controller
{
    // Menampilkan semua order
    public function index()
    {
        $orders = Order::with(['user','items'])
            ->orderBy('created_at', 'DESC')
            ->get()
            ->map(function($order) {
                return [
                    'order_id' => $order->order_id,
                    'order_date' => $order->created_at ? $order->created_at->format('Y-m-d H:i:s') : 'N/A',
                    'total_price' => $order->total_price ?? 0,
                    'status' => ucfirst($order->status ?? 'pending'),
                    'username' => $order->user->username ?? '-',
                    'metode_pembayaran' => $order->payment_method ?? '-',
                    'bank_name' => $order->bank_name ?? '-',
                    'ewallet_provider_name' => $order->ewallet_provider_name ?? '-',
                    'nama_produk_list' => $order->items->pluck('product_name')->implode('<br>')
                ];
            });

        return view('admin.ordersAdmin.ordersAdmin', compact('orders'));
    }

    // Detail order
    public function show($order_id)
    {
        $order = Order::with(['user','items','address'])->find($order_id);

        if(!$order) abort(404, "Order #{$order_id} tidak ditemukan.");

        $order_details = [
            'order_id' => $order->id,
            'order_date' => $order->created_at->format('Y-m-d H:i:s'),
            'total_price' => $order->total_price,
            'metode_pembayaran' => $order->payment_method,
            'bank_name' => $order->bank_name,
            'ewallet_provider_name' => $order->ewallet_provider_name,
            'status' => ucfirst($order->status),
            'nama_penerima' => $order->address->nama_penerima ?? '-',
            'no_telepon' => $order->address->no_telepon ?? '-',
            'email' => $order->user->email ?? '-',
            'alamat_lengkap' => $order->address->alamat_lengkap ?? '-',
            'kota' => $order->address->kota ?? '-',
            'provinsi' => $order->address->provinsi ?? '-',
            'kode_pos' => $order->address->kode_pos ?? '-',
            'kurir' => $order->kurir ?? 'JNE Reguler'
        ];

        $order_items_data = $order->items->map(function($item){
            return [
                'nama' => $item->product_name,
                'qty' => $item->qty,
                'harga' => $item->price,
                'gambar_produk' => $item->image,
                'deskripsi' => $item->description
            ];
        });

        $sub_total_calculated = $order->items->sum(fn($item) => $item->qty * $item->price);
        $discount_amount = $order->discount ?? 0;
        $shipping_charge = $order->shipping_cost ?? 25000;
        $estimated_tax = 0;

        return view('admin.ordersAdmin.orderDetail', compact(
            'order_details', 'order_items_data', 'sub_total_calculated',
            'discount_amount','shipping_charge','estimated_tax'
        ));
    }

    // Update status order
    public function updateStatus(Request $request)
    {
        $request->validate([
            'order_id' => 'required|integer|exists:orders,order_id',
            'status' => 'required|string|in:pending,prepared,shipped,completed'
        ]);

        $order = Order::find($request->order_id);
        $order->status = $request->status;
        $order->save();

        return response()->json([
            'success' => true,
            'message' => "Status order #{$order->id} berhasil diperbarui ke " . ucfirst($order->status)
        ]);
    }
}
