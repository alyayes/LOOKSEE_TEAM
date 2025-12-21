<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OrdersAdminController extends Controller
{
    public function index()
    {
        $latest_orders = Order::with(['user', 'items.produk', 'payment.method'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($order) {
                $productNames = $order->items->map(function($item) {
                    return $item->produk ? $item->produk->nama_produk : 'Produk Dihapus';
                })->join(', ');

                $paymentMethod = '-';
                if ($order->payment && $order->payment->method) {
                    $paymentMethod = $order->payment->method->method_name;
                }

                return [
                    'order_id' => $order->order_id,
                    'order_date' => $order->created_at ? $order->created_at->format('Y-m-d H:i:s') : '-',
                    'total_price' => $order->grand_total,
                    'metode_pembayaran' => $paymentMethod,
                    'status' => $order->status,
                    'username' => $order->user ? $order->user->username : 'Guest',
                    'nama_produk_list' => $productNames ?: '-',
                ];
            });

        return view('admin.ordersAdmin.ordersAdmin', compact('latest_orders'));
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,order_id',
            'status' => 'required|in:pending,prepared,shipped,completed'
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

    public function show($order_id)
    {
        $order = Order::with(['user', 'items.produk', 'payment.method'])->findOrFail($order_id);
        return view('admin.ordersAdmin.orderDetail', compact('order'));
    }
}
