<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Produk;
use App\Models\Order;
use App\Models\OrderItem;

class DashboardAdminController extends Controller
{
    public function index()
    {
        // =============================
        // 1. DATA KARTU (COUNT)
        // =============================
        $data_cards = [
            'user_count'      => User::count(),
            'product_count'   => Produk::count(),
            'order_count'     => Order::count(),
            'total_sales'     => Order::where('status', 'completed')->sum('total_price'),
        ];

        // =============================
        // 2. ORDER TERBARU
        // =============================
        $latest_orders = Order::with(['user', 'items.produk'])
            ->orderByDesc('created_at')
            ->take(5)
            ->get()
            ->map(function ($order) {

                // List nama produk
                $nama_produk_list = $order->items->map(function ($item) {
                    return $item->produk->nama_produk ?? 'Produk Tidak Ditemukan';
                })->implode('<br>');

                return [
                    "order_id"          => $order->id,
                    "order_date"        => $order->created_at->format('Y-m-d H:i:s'),
                    "total_price"       => $order->total_price,
                    "metode_pembayaran" => $order->metode_pembayaran,
                    "status"            => $order->status,
                    "username"          => $order->user->username ?? 'Unknown',
                    "nama_produk_list"  => $nama_produk_list,
                ];
            });

        return view('admin.dashboardAdmin.dashboardAdmin', [
            ...$data_cards,
            'latest_orders' => $latest_orders,
        ]);
    }

    public function updateOrderStatus(Request $request)
    {
        $orderId = $request->input('order_id');
        $newStatus = $request->input('status');

        if (!$orderId || !$newStatus) {
            return response()->json(['success' => false, 'message' => 'Order ID atau status tidak valid.'], 400);
        }

        $order = Order::find($orderId);
        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Order tidak ditemukan.'], 404);
        }

        // UPDATE
        $order->status = $newStatus;
        $order->save();

        return response()->json([
            'success' => true,
            'message' => "Status Order ID {$orderId} berhasil diperbarui menjadi {$newStatus}."
        ]);
    }
}
