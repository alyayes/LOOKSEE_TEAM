<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function list(Request $request)
    {
        $status_filter = $request->status ?? 'all';

        // Hitung jumlah order berdasarkan status untuk badge
        $order_counts = [
            'all'       => Order::where('user_id', auth()->id())->count(),
            'pending'   => Order::where('user_id', auth()->id())->where('status', 'pending')->count(),
            'prepared'  => Order::where('user_id', auth()->id())->where('status', 'prepared')->count(),
            'shipped'   => Order::where('user_id', auth()->id())->where('status', 'shipped')->count(),
            'completed' => Order::where('user_id', auth()->id())->where('status', 'completed')->count(),
            'cancelled' => Order::where('user_id', auth()->id())->where('status', 'cancelled')->count(),
        ];

        // Ambil data orders sesuai filter
        $orders_query = Order::where('user_id', auth()->id())->orderBy('order_date', 'desc');

        if ($status_filter !== 'all') {
            $orders_query->where('status', $status_filter);
        }

        $orders = $orders_query->get()->map(function ($order) {
            $items = OrderItem::where('order_id', $order->order_id)
                ->join('products', 'order_items.product_id', '=', 'products.product_id')
                ->select(
                    'products.nama_produk',
                    'products.gambar_produk',
                    'order_items.quantity',
                    'order_items.price_at_purchase'
                )->get()->toArray();

            return [
                'order_id'      => $order->order_id,
                'order_date'    => $order->order_date,
                'status'        => $order->status,
                'total_price'   => $order->grand_total,
                'items'         => $items,
            ];
        });

        return view('orders.list', compact('orders', 'order_counts', 'status_filter'));
    }
}
