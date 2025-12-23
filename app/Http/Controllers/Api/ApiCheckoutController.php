<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\CartsItems;
use App\Models\Produk;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\UserAddress;
use App\Models\OrderPayment;

class ApiCheckoutController extends Controller
{
    public function getCheckoutData(Request $request)
    {
        $user = $request->user();
        $selected = $request->query('selected_products');

        if ($selected) {
            $ids = array_filter(explode(',', $selected));
            $cartItems = CartsItems::where('user_id', $user->user_id)->whereIn('cart_item_id', $ids)->with('produk')->get();
        } else {
            $cartItems = CartsItems::where('user_id', $user->user_id)->with('produk')->get();
        }

        $subtotal = $cartItems->reduce(function ($carry, $item) {
            return $carry + ($item->quantity * ($item->produk->harga ?? 0));
        }, 0);

        $shipping = 0; // placeholder
        $grand = $subtotal + $shipping;

        $addresses = UserAddress::where('user_id', $user->user_id)->get();

        return response()->json([
            'success' => true,
            'items' => $cartItems,
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'grand_total' => $grand,
            'addresses' => $addresses,
        ]);
    }

    public function processCheckout(Request $request)
    {
        $data = $request->validate([
            'address_id' => 'nullable|integer',
            'payment_method_id' => 'nullable|integer',
            'selected_products' => 'nullable|string', // comma separated cart_item_id
            'shipping_method' => 'nullable|string'
        ]);

        $user = $request->user();
        $selected = $data['selected_products'] ?? null;

        if ($selected) {
            $ids = array_filter(explode(',', $selected));
            $cartItems = CartsItems::where('user_id', $user->user_id)->whereIn('cart_item_id', $ids)->with('produk')->get();
        } else {
            $cartItems = CartsItems::where('user_id', $user->user_id)->with('produk')->get();
        }

        if ($cartItems->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'No items to checkout'], 400);
        }

        $subtotal = $cartItems->reduce(function ($carry, $item) {
            return $carry + ($item->quantity * ($item->produk->harga ?? 0));
        }, 0);

        $shipping = 0; // calculate as needed
        $grand = $subtotal + $shipping;

        DB::beginTransaction();
        try {
            $address = null;
            if (!empty($data['address_id'])) {
                $address = UserAddress::where('id', $data['address_id'])->where('user_id', $user->user_id)->first();
            }

            $order = Order::create([
                'user_id' => $user->user_id,
                'address_id' => $address?->id,
                'nama_penerima' => $address?->receiver_name ?? $user->name,
                'no_telepon' => $address?->phone_number ?? null,
                'alamat_lengkap' => $address?->full_address ?? null,
                'kota' => $address?->city ?? null,
                'provinsi' => $address?->province ?? null,
                'kode_pos' => $address?->postal_code ?? null,
                'total_price' => $subtotal,
                'shipping_cost' => $shipping,
                'grand_total' => $grand,
                'status' => 'pending',
                'order_date' => Carbon::now(),
                'shipping_method' => $data['shipping_method'] ?? null,
            ]);

            foreach ($cartItems as $ci) {
                OrderItem::create([
                    'order_id' => $order->order_id,
                    'id_produk' => $ci->id_produk,
                    'quantity' => $ci->quantity,
                    'price_at_purchase' => $ci->produk->harga ?? 0,
                ]);
            }

            // record a payment row (initial, pending)
            if (!empty($data['payment_method_id'])) {
                OrderPayment::create([
                    'order_id' => $order->order_id,
                    'method_id' => $data['payment_method_id'],
                    'amount' => $grand,
                    'transaction_status' => 'pending',
                ]);
            }

            // remove items from cart
            $cartIds = $cartItems->pluck('cart_item_id')->toArray();
            CartsItems::whereIn('cart_item_id', $cartIds)->delete();

            DB::commit();
            return response()->json(['success' => true, 'order_id' => $order->order_id, 'order' => $order->load('items.produk')]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
