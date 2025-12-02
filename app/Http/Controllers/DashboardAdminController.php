<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Produk;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardAdminController extends Controller
{
    public function index()
    {
        $user_count = User::count();
        $product_count = Produk::count();
        $order_count = Order::count();
        $total_sales = Order::where('status', '!=', 'cancelled')->sum('grand_total');

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

        return view('admin.dashboardAdmin.dashboardAdmin', compact(
            'latest_orders', 
            'user_count', 
            'product_count', 
            'order_count',
            'total_sales' 
        ));
    }
}