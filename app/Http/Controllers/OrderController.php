<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Produk;
use App\Models\User;
use App\Models\UserAddress; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function listOrders(Request $request)
    {
        $status_filter = $request->status ?? 'all';
        $userId = Auth::id(); 

        $order_counts = [
            'all'       => Order::where('user_id', $userId)->count(),
            'pending'   => Order::where('user_id', $userId)->where('status', 'pending')->count(),
            'prepared'  => Order::where('user_id', $userId)->where('status', 'prepared')->count(),
            'shipped'   => Order::where('user_id', $userId)->where('status', 'shipped')->count(),
            'completed' => Order::where('user_id', $userId)->where('status', 'completed')->count(),
        ];

        $orders_query = Order::where('user_id', $userId)->orderBy('order_date', 'desc');

        if ($status_filter !== 'all') {
            $orders_query->where('status', $status_filter);
        }

        $orders = $orders_query->get()->map(function ($order) {
            $items = OrderItem::where('order_id', $order->order_id)
                ->join('produk_looksee', 'order_items.id_produk', '=', 'produk_looksee.id_produk')
                ->select(
                    'produk_looksee.nama_produk',
                    'produk_looksee.gambar_produk',
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

    public function getOrderDetailsAjax($order_id)
    {
        $userId = Auth::id();
        $order = Order::where('order_id', $order_id)
                      ->where('user_id', $userId)
                      ->first();

        if (!$order) {
            return response('Order not found or unauthorized', 404);
        }

        // --- PERBAIKAN: AMBIL DATA ALAMAT MANUAL ---
        $addr = UserAddress::find($order->address_id);

        $items = OrderItem::where('order_id', $order->order_id)
            ->join('produk_looksee', 'order_items.id_produk', '=', 'produk_looksee.id_produk')
            ->select(
                'produk_looksee.nama_produk',
                'produk_looksee.gambar_produk',
                'order_items.quantity',
                'order_items.price_at_purchase'
            )->get()->toArray();
        
        $payment = $order->payment;
        $payment_method = $payment ? ($payment->method ? $payment->method->method_name : 'N/A') : 'N/A';
        $payment_detail = '';

        if ($payment) {
            if ($payment->bankDetail) {
                $payment_detail = $payment->bankDetail->bank_name;
            } elseif ($payment->ewalletDetail) {
                $payment_detail = $payment->ewalletDetail->ewallet_provider_name;
            }
        }

        $order_detail = [
            'order_id'         => $order->order_id,
            'order_date'       => $order->order_date,
            'status'           => $order->status,
            'total_price'      => $order->grand_total,
            
            // --- DATA ALAMAT DIAMBIL DARI VARIABLE $addr ---
            'nama_penerima'    => $addr ? $addr->receiver_name : 'N/A',
            'no_telepon'       => $addr ? $addr->phone_number : 'N/A',
            'alamat_lengkap'   => $addr ? $addr->full_address : '',
            'kota'             => $addr ? $addr->city : '',
            'provinsi'         => $addr ? $addr->province : '',
            'kode_pos'         => $addr ? $addr->postal_code : '',
            // ------------------------------------------------
            
            'kurir'            => $order->shipping_method,
            'items'            => $items,
            'payment_method'   => $payment_method,
            'payment_detail'   => $payment_detail,
            'transaction_code' => $payment ? $payment->transaction_code : '-'
        ];

        return view('orders._details_modal_content', compact('order_detail'));
    }

    public function updateStatus(Request $request)
    {
        $order = Order::find($request->order_id);
        if ($order) {
            $order->status = $request->status;
            $order->save();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false], 400);
    }
}