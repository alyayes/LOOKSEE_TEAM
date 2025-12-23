<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class ApiOrderController extends Controller
{
    public function listOrders(Request $request)
    {
        $user = $request->user();
        $orders = Order::where('user_id', $user->user_id)->with('items.produk', 'payment')->orderBy('order_date', 'desc')->get();
        return response()->json(['success' => true, 'data' => $orders]);
    }

    public function getOrderDetails(Request $request, $id)
    {
        $user = $request->user();
        $order = Order::where('order_id', $id)->where('user_id', $user->user_id)->with('items.produk', 'payment')->first();
        if (! $order) {
            return response()->json(['success' => false, 'message' => 'Order not found'], 404);
        }
        return response()->json(['success' => true, 'data' => $order]);
    }
}
