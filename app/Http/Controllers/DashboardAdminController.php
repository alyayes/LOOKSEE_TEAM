<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardAdminController extends Controller
{

    public function index()
    {
        $data_cards = [
            'user_count' => 11,               
            'product_count' => 53,            
            'order_count' => 42,              
            'total_sales' => 15015500.00,     
        ];

        $latest_orders = [
            [
                "order_id" => 48,
                "order_date" => "2025-06-16 12:57:24",
                "total_price" => 347000.00,
                "metode_pembayaran" => "Bank Transfer",
                "status" => "pending", 
                "username" => "whoolyy",
                "nama_produk_list" => "Shenning Knit<br>Executive Sleeve Stripes"
            ],
            [
                "order_id" => 44,
                "order_date" => "2025-06-11 15:59:19",
                "total_price" => 202000.00,
                "metode_pembayaran" => "Bank Transfer",
                "status" => "completed", 
                "username" => "luuccy_",
                "nama_produk_list" => "Basic Top<br>Shenning Knit"
            ],
            [
                "order_id" => 43,
                "order_date" => "2025-06-11 13:58:34",
                "total_price" => 360000.00,
                "metode_pembayaran" => "Bank Transfer",
                "status" => "completed", 
                "username" => "veliya",
                "nama_produk_list" => "Denim Overshirt"
            ],
            [
                "order_id" => 42,
                "order_date" => "2025-06-10 15:22:32",
                "total_price" => 232500.00,
                "metode_pembayaran" => "Bank Transfer",
                "status" => "pending", 
                "username" => "dior",
                "nama_produk_list" => "Red Shirt<br>Sheen Pashmina Silk<br>Ribonnie"
            ],
            [
                "order_id" => 32,
                "order_date" => "2025-06-10 09:13:05",
                "total_price" => 1799500.00,
                "metode_pembayaran" => "Bank Transfer",
                "status" => "prepared", 
                "username" => "carlotee_",
                "nama_produk_list" => "Grizzly Rompi<br>Sheen Pashmina Silk<br>Sepatu Nike<br>Jeans Boyfriend<br>Cargo Loose Jeans<br>Hyunbin Kaos Polos<br>Ladiesbag<br>Executive Sleeve Stripes<br>Loose Pants<br>Gia Jeans Highwaist"
            ],
        ];

        $data = array_merge($data_cards, [
            'latest_orders' => $latest_orders,
        ]);

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