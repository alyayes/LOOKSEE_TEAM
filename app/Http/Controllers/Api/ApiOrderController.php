<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\UserAddress; // <--- PENTING: Import Model Alamat
use Illuminate\Support\Facades\Auth;

class ApiOrderController extends Controller
{
    public function listOrders(Request $request)
    {
        $userId = Auth::id();
        
        // Kita load items.produk dan payment saja
        $orders = Order::where('user_id', $userId)
                       ->with('items.produk', 'payment') 
                       ->orderBy('order_date', 'desc')
                       ->get();

        return response()->json(['success' => true, 'data' => $orders]);
    }

    public function getOrderDetails(Request $request, $order_id)
    {
        $userId = Auth::id();

        if (!$userId) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        // Ambil Order
        $order = Order::where('order_id', $order_id)
                      ->where('user_id', $userId)
                      ->with(['items.produk', 'payment']) // Tanpa 'address' karena model ga diubah
                      ->first();

        if (!$order) {
            return response()->json([
                'status' => 'error',
                'message' => 'Order not found'
            ], 404);
        }

        // --- SOLUSI TANPA UBAH MODEL ---
        // Kita cari manual alamatnya berdasarkan address_id yang ada di order
        $addr = UserAddress::find($order->address_id);

        // Get Items (Mapping biar rapi)
        $items = $order->items->map(function($item) {
            return [
                'nama_produk' => $item->produk->nama_produk ?? 'Unknown Product',
                'gambar_produk' => $item->produk->gambar_produk ?? null,
                'quantity' => $item->quantity,
                'price_at_purchase' => $item->price_at_purchase
            ];
        });

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

        // Susun Data JSON
        $data = [
            'order_id'         => $order->order_id,
            'order_date'       => $order->order_date,
            'status'           => $order->status,
            'total_price'      => $order->grand_total,
            
            // ISI DATA ALAMAT DARI VARIABEL $addr MANUAL TADI
            'nama_penerima'    => $addr ? $addr->receiver_name : 'N/A',
            'no_telepon'       => $addr ? $addr->phone_number : 'N/A',
            'alamat_lengkap'   => $addr ? $addr->full_address : '',
            'kota'             => $addr ? $addr->city : '',
            'provinsi'         => $addr ? $addr->province : '',
            'kode_pos'         => $addr ? $addr->postal_code : '',
            
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