<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth; 

class ApiOrderController extends Controller
{
    public function listOrders(Request $request)
    {
        $userId = Auth::id(); 

        $orders = Order::where('user_id', $userId)
                       ->with('items.produk', 'payment')
                       ->orderBy('order_date', 'desc')
                       ->get();

        return response()->json(['success' => true, 'data' => $orders]);
    }

    public function getOrderDetails(Request $request, $id)
    {
        $userId = Auth::id();

        $order = Order::where('order_id', $id)
                      ->where('user_id', $userId)
                      ->with('items.produk', 'payment')
                      ->first();

        if (! $order) {
            return response()->json(['success' => false, 'message' => 'Order not found'], 404);
        }
        
        return response()->json(['success' => true, 'data' => $order]);
    }
}