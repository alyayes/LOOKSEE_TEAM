<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiOrderController extends Controller
{
    /**
     * List orders for the authenticated user.
     */
    public function listOrders(Request $request)
    {
        // 1. Get authenticated user ID
        $userId = Auth::id();

        if (!$userId) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 401);
        }

        $status_filter = $request->status ?? 'all';

        // 2. Query Orders
        $orders_query = Order::where('user_id', $userId)->orderBy('order_date', 'desc');

        if ($status_filter !== 'all') {
            $orders_query->where('status', $status_filter);
        }

        // 3. Format Data
        $orders = $orders_query->get()->map(function ($order) {
            $items = OrderItem::where('order_id', $order->order_id)
                ->join('produk_looksee', 'order_items.id_produk', '=', 'produk_looksee.id_produk')
                ->select(
                    'produk_looksee.nama_produk',
                    'produk_looksee.gambar_produk',
                    'order_items.quantity',
                    'order_items.price_at_purchase'
                )->get();

            return [
                'order_id'      => $order->order_id,
                'order_date'    => $order->order_date,
                'status'        => $order->status,
                'total_price'   => $order->grand_total,
                'items'         => $items,
            ];
        });

        // 4. Return JSON
        return response()->json([
            'status' => 'success',
            'data' => [
                'orders' => $orders,
                'filter' => $status_filter
            ]
        ], 200);
    }

    /**
     * Get details of a specific order.
     */
    public function getOrderDetails(Request $request, $order_id)
    {
        $userId = Auth::id();

        if (!$userId) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        // Security check: ensure order belongs to user
        $order = Order::where('order_id', $order_id)
                      ->where('user_id', $userId)
                      ->first();

        if (!$order) {
            return response()->json([
                'status' => 'error',
                'message' => 'Order not found or unauthorized'
            ], 404);
        }

        // Get Items
        $items = OrderItem::where('order_id', $order->order_id)
            ->join('produk_looksee', 'order_items.id_produk', '=', 'produk_looksee.id_produk')
            ->select(
                'produk_looksee.nama_produk',
                'produk_looksee.gambar_produk',
                'order_items.quantity',
                'order_items.price_at_purchase'
            )->get();

        // Get Payment Info
        $payment = $order->payment;
        $payment_method = $payment && $payment->method ? $payment->method->method_name : 'N/A';
        $payment_detail = '';
        $transaction_code = $payment ? $payment->transaction_code : '-';

        if ($payment) {
            if ($payment->bankDetail) {
                $payment_detail = $payment->bankDetail->bank_name;
            } elseif ($payment->ewalletDetail) {
                $payment_detail = $payment->ewalletDetail->ewallet_provider_name;
            }
        }

        $data = [
            'order_id'         => $order->order_id,
            'order_date'       => $order->order_date,
            'status'           => $order->status,
            'total_price'      => $order->grand_total,
            'nama_penerima'    => $order->nama_penerima,
            'no_telepon'       => $order->no_telepon,
            'alamat_lengkap'   => $order->alamat_lengkap,
            'kota'             => $order->kota,
            'provinsi'         => $order->provinsi,
            'kode_pos'         => $order->kode_pos,
            'kurir'            => $order->shipping_method,
            'payment_method'   => $payment_method,
            'payment_detail'   => $payment_detail,
            'transaction_code' => $transaction_code,
            'items'            => $items,
        ];

        return response()->json([
            'status' => 'success',
            'data' => $data
        ], 200);
    }
}