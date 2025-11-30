<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;

class OrdersAdminController extends Controller
{
    /**
     * Menampilkan semua order (sorted by latest)
     */
    public function index()
    {
        $orders = Order::with(['user', 'items'])
        ->orderBy('created_at', 'DESC')
        ->get()
        ->map(function ($order) {
            return [
                'order_id'   => $order->id,
                'order_date' => $order->created_at
                    ? $order->created_at->format('Y-m-d H:i:s')
                    : 'N/A',
                'total_price' => $order->total_price ?? 0,
                'status'     => ucfirst($order->status ?? 'pending'),
                'username'   => $order->user->username ?? '-',

                // 🔥 FIELD YANG DIBUTUHKAN OLEH BLADE
                'metode_pembayaran'     => $order->payment_method ?? '-',
                'bank_name'             => $order->bank_name ?? '-',
                'ewallet_provider_name' => $order->ewallet_provider_name ?? '-',

                'nama_produk_list' => $order->items->pluck('product_name')->implode('<br>')
            ];
        });


        return view('admin/ordersAdmin.ordersAdmin', compact('orders'));
    }

    /**
     * Halaman detail order berdasarkan order_id
     */
    public function show($order_id)
    {
        $order = Order::with(['items', 'user', 'address'])
            ->where('id', $order_id)
            ->first();

        if (!$order) {
            abort(404, "Order ID #{$order_id} tidak ditemukan.");
        }

        // Detail penerima
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

        // Detail produk
        $order_itemss_data = $order->items->map(function ($item) {
            return [
                'nama'   => $item->product_name,
                'qty'    => $item->qty,
                'harga'  => $item->price,
                'gambar_produk' => $item->image,
                'deskripsi' => $item->description,
            ];
        });

        // Hitung subtotal
        $sub_total_calculated = $order->items->sum(function ($item) {
            return $item->qty * $item->price;
        });

        $discount_amount = $order->discount ?? 0;
        $shipping_charge = $order->shipping_cost ?? 25000;
        $estimated_tax = 0;

        return view('admin/ordersAdmin.orderDetail', compact(
            'order_details',
            'order_itemss_data',
            'sub_total_calculated',
            'discount_amount',
            'shipping_charge',
            'estimated_tax'
        ));
    }

    /**
     * Update status order di database
     */
    public function updateStatus(Request $request)
    {
        $request->validate([
            'order_id' => 'required|integer|exists:orders,order_id',
            'status'   => 'required|string|in:pending,prepared,shipped,completed',
        ]);

        $order = Order::find($request->order_id);
        $order->status = $request->status;
        $order->save();

        return response()->json([
            'success' => true,
            'message' => 'Status order #' . $order->order_id . ' berhasil diperbarui ke ' . ucfirst($order->status)
        ]);
    }

}
