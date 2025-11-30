<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardAdminController extends Controller
{

    public function index()
{
    // Hitung data card
    $data_cards = [
        'user_count' => User::count(),
        'product_count' => Product::count(),
        'order_count' => Order::count(),
        'total_sales' => Order::sum('total_price'),
    ];

    // Ambil latest orders (misal 5 order terbaru) beserta relasi user dan produk
    $latest_orders = Order::with('user', 'products')
        ->orderBy('created_at', 'desc')
        ->take(5)
        ->get()
        ->map(function ($order) {
            return [
                'order_id' => $order->id,
                'order_date' => $order->created_at->format('Y-m-d H:i:s'),
                'total_price' => $order->total_price,
                'metode_pembayaran' => $order->payment_method,
                'status' => $order->status,
                'username' => $order->user->username ?? '-',
                'nama_produk_list' => $order->products->map(fn($p) => $p->name)->implode('<br>'),
            ];
        });

    $data = array_merge($data_cards, ['latest_orders' => $latest_orders]);

    return view('admin.dashboardAdmin.dashboardAdmin', $data);
}


    
    public function updateOrderStatus(Request $request)
    {
        $orderId = $request->input('order_id');
        $newStatus = $request->input('status');

        if (!$orderId || !$newStatus) {
            return response()->json(['success' => false, 'message' => 'Order ID atau status tidak valid.'], 400);
        }

        \Log::info("SIMULASI: Order ID: {$orderId} status diperbarui menjadi: {$newStatus}");

        return response()->json([
            'success' => true,
            'message' => "Status Order ID {$orderId} berhasil diperbarui menjadi {$newStatus} (SIMULASI BERHASIL)."
        ]);
    }
}