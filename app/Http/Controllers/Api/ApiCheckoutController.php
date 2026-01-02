<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CartsItems;
use App\Models\UserAddress;
use App\Models\PaymentMethod;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderPayment;
use App\Models\BankTransferDetail;
use App\Models\EwalletTransferDetail;
use Illuminate\Support\Str;

class ApiCheckoutController extends Controller
{
    public function getCheckoutData(Request $request)
    {
        $userId = Auth::id();
        $selectedIdsString = $request->query('selected_products');

        if (!$selectedIdsString) {
            return response()->json(['status' => 'error', 'message' => 'Pilih produk terlebih dahulu.'], 400);
        }

        $selectedIds = explode(',', $selectedIdsString);

        $cartItems = CartsItems::where('user_id', $userId)
                    ->whereIn('product_id', $selectedIds)
                    ->with('produk')
                    ->get();

        $totalPrice = 0;
        $itemsFormatted = $cartItems->map(function($item) use (&$totalPrice) {
            $harga = $item->produk ? $item->produk->harga : 0;
            $subtotal = $harga * $item->quantity;
            $totalPrice += $subtotal;
            return [
                'product_id' => $item->product_id,
                'nama_produk' => $item->produk ? $item->produk->nama_produk : 'Unknown',
                'gambar_produk' => $item->produk ? asset('assets/images/produk-looksee/' . $item->produk->gambar_produk) : '',
                'harga' => $harga,
                'quantity' => $item->quantity,
                'subtotal' => $subtotal
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => [
                'items' => $itemsFormatted,
                'summary' => [
                    'total_price' => $totalPrice,
                    'shipping_cost' => 20000,
                    'grand_total' => $totalPrice + 20000,
                ],
                'addresses' => UserAddress::where('user_id', $userId)->get(),
                'payment_options' => [
                    'banks' => BankTransferDetail::all(),
                    'ewallets' => EwalletTransferDetail::all()
                ]
            ]
        ]);
    }

    public function processCheckout(Request $request)
    {
        $userId = Auth::id();
        
        $request->validate([
            'selected_products' => 'required',
            'address_id'        => 'required|exists:user_address,id', // Fix: Nama tabel tanpa 'es'
            'payment_method'    => 'required', 
        ]);

        $selectedIds = explode(',', $request->selected_products);

        DB::beginTransaction();
        try {
            $cartItems = CartsItems::where('user_id', $userId)
                        ->whereIn('product_id', $selectedIds)
                        ->with('produk')
                        ->get();
            
            if ($cartItems->isEmpty()) throw new \Exception("Keranjang kosong.");

            $totalPrice = 0;
            foreach ($cartItems as $item) {
                if ($item->produk) {
                    if ($item->produk->stock < $item->quantity) {
                        throw new \Exception("Stok {$item->produk->nama_produk} tidak cukup.");
                    }
                    $totalPrice += $item->produk->harga * $item->quantity;
                }
            }
            
            $grandTotal = $totalPrice + 20000;

            $order = Order::create([
                'user_id' => $userId,
                'address_id' => $request->address_id,
                'total_price' => $totalPrice,
                'shipping_cost' => 20000,
                'grand_total' => $grandTotal,
                'status' => 'pending',
                'order_date' => now(),
            ]);

            foreach ($cartItems as $item) {
                $item->produk->decrement('stock', $item->quantity);
                OrderItem::create([
                    'order_id' => $order->order_id,
                    'id_produk' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price_at_purchase' => $item->produk->harga
                ]);
            }

            $methodName = $request->payment_method;
            $payMethodModel = PaymentMethod::where('method_name', $methodName)->first();
            $methodId = $payMethodModel ? $payMethodModel->method_id : 3; // Default COD

            OrderPayment::create([
                'order_id' => $order->order_id,
                'method_id' => $methodId,
                'amount' => $grandTotal,
                'transaction_status' => 'pending',
                'transaction_code' => 'TRX-' . strtoupper(Str::random(10)),
                'bank_payment_id_fk' => $request->bank_id,
                'e_wallet_payment_id_fk' => $request->ewallet_id
            ]);

            CartsItems::where('user_id', $userId)->whereIn('product_id', $selectedIds)->delete();

            DB::commit();
            return response()->json(['status' => 'success', 'data' => ['order_id' => $order->order_id]], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function addAddress(Request $request) {
        $request->validate(['receiver_name'=>'required','phone_number'=>'required','full_address'=>'required','city'=>'required','district'=>'required','province'=>'required','postal_code'=>'required']);
        $address = UserAddress::create(array_merge($request->all(), ['user_id' => Auth::id()]));
        return response()->json(['status' => 'success', 'data' => $address]);
    }

    public function updateAddress(Request $request, $id) {
        $address = UserAddress::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $address->update($request->all());
        return response()->json(['status' => 'success', 'data' => $address]);
    }

    public function deleteAddress($id) {
        UserAddress::where('id', $id)->where('user_id', Auth::id())->delete();
        return response()->json(['status' => 'success']);
    }
}