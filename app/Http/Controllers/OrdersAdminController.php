<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrdersAdminController extends Controller
{
    public function index()
    {
        $orders = Order::with(['user', 'items.produk', 'payment.method', 'payment.bankDetail', 'payment.ewalletDetail'])
            ->orderBy('created_at', 'DESC')
            ->get()
            ->map(function($order) {
                
                $payment_method = '-';
                $bank_ewallet_info = '-';
                
                if ($order->payment) {
                    $payment_method = $order->payment->method->method_name ?? '-';
                    if ($payment_method == 'Bank Transfer' && $order->payment->bankDetail) {
                        $bank_ewallet_info = $order->payment->bankDetail->bank_name;
                    } elseif ($payment_method == 'E-Wallet' && $order->payment->ewalletDetail) {
                        $bank_ewallet_info = $order->payment->ewalletDetail->ewallet_provider_name;
                    }
                }

                $productList = $order->items->map(function($item) {
                    return $item->produk ? $item->produk->nama_produk : 'Produk Dihapus';
                })->join('<br>');

                return [
                    'order_id' => $order->order_id,
                    'order_date' => $order->created_at ? $order->created_at->format('Y-m-d H:i:s') : 'N/A',
                    'total_price' => $order->grand_total ?? 0,
                    'status' => ucfirst($order->status ?? 'pending'),
                    'username' => $order->user ? $order->user->username : 'Guest',
                    'metode_pembayaran' => $payment_method,
                    'bank_name' => $bank_ewallet_info, 
                    'ewallet_provider_name' => $bank_ewallet_info,
                    'nama_produk_list' => $productList
                ];
            });

        return view('admin.ordersAdmin.ordersAdmin', compact('orders'));
    }

    public function show($order_id)
    {
        $order = Order::with(['user', 'items.produk', 'payment.method', 'payment.bankDetail', 'payment.ewalletDetail'])->find($order_id);

        if(!$order) abort(404, "Order #{$order_id} tidak ditemukan.");

        $payment_method_name = '-';
        $bank_name = '-';
        $ewallet_name = '-';

        if ($order->payment) {
            $payment_method_name = $order->payment->method->method_name ?? '-';
            if ($order->payment->bankDetail) {
                $bank_name = $order->payment->bankDetail->bank_name;
            } elseif ($order->payment->ewalletDetail) {
                $ewallet_name = $order->payment->ewalletDetail->ewallet_provider_name;
            }
        }

        // AMBIL TANGGAL YANG BENAR (Prioritaskan order_date, kalau gak ada baru created_at)
        $orderDate = $order->order_date 
            ? \Carbon\Carbon::parse($order->order_date)->format('Y-m-d H:i:s') 
            : ($order->created_at ? $order->created_at->format('Y-m-d H:i:s') : '-');

        $order_details = [
            'order_id' => $order->order_id,
            'order_date' => $orderDate, // Pakai variabel yang sudah divalidasi di atas
            'total_price' => $order->grand_total,
            'metode_pembayaran' => $payment_method_name,
            'bank_name' => $bank_name,
            'ewallet_provider_name' => $ewallet_name,
            'status' => ucfirst($order->status),
            'nama_penerima' => $order->nama_penerima ?? '-',
            'no_telepon' => $order->no_telepon ?? '-',
            'email' => $order->user->email ?? '-',
            'alamat_lengkap' => $order->alamat_lengkap ?? '-',
            'kota' => $order->kota ?? '-',
            'provinsi' => $order->provinsi ?? '-',
            'kode_pos' => $order->kode_pos ?? '-',
            'kurir' => $order->shipping_method ?? 'Regular Shipping'
        ];

        $order_items_data = $order->items->map(function($item){
            return [
                'nama' => $item->produk->nama_produk ?? 'Produk Dihapus',
                'qty' => $item->quantity,
                'harga' => $item->price_at_purchase,
                'gambar_produk' => $item->produk->gambar_produk ?? null,
                'deskripsi' => $item->produk->deskripsi ?? '-'
            ];
        });

        $sub_total_calculated = $order->items->sum(fn($item) => $item->quantity * $item->price_at_purchase);
        $discount_amount = 0;
        $shipping_charge = $order->shipping_cost ?? 20000;
        $estimated_tax = 0;

        return view('admin.ordersAdmin.orderDetail', compact(
            'order_details', 'order_items_data', 'sub_total_calculated',
            'discount_amount','shipping_charge','estimated_tax'
        ));
    }
    
    public function updateStatus(Request $request)
    {
        $request->validate([
            'order_id' => 'required|integer|exists:orders,order_id',
            'status' => 'required|string|in:pending,prepared,shipped,completed'
        ]);

        $order = Order::find($request->order_id);
        $order->status = $request->status;
        $order->save();

        return response()->json([
            'success' => true,
            'message' => "Status order #{$order->order_id} berhasil diperbarui ke " . ucfirst($order->status)
        ]);
    }
}