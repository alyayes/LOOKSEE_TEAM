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
        $selected = $request->query('selected_products'); 

        return response()->json(['message' => 'Gunakan function yang lama untuk getCheckoutData']);
    }

    public function processCheckout(Request $request)
    {
        $userId = Auth::id();
        
        $request->validate([
            'selected_products' => 'required',
            'address_id'        => 'required|exists:user_address,id',
            'payment_method'    => 'required', 
            'bank_id'           => 'required_if:payment_method,Bank Transfer',
            'ewallet_id'        => 'required_if:payment_method,E-Wallet',
        ]);

        $selectedIds = explode(',', $request->selected_products);

        DB::beginTransaction();
        try {
            // 2. Hitung Ulang Total
            $cartData = CartsItems::with('produk')
                        ->where('user_id', $userId)
                        ->whereIn('product_id', $selectedIds)
                        ->get();
            
            if ($cartData->isEmpty()) throw new \Exception("Produk cart tidak valid");

            $totalPrice = 0;
            foreach ($cartData as $item) {
                if ($item->produk) {
                    $totalPrice += $item->produk->harga * $item->quantity;
                }
            }
            $shippingCost = 20000;
            $grandTotal = $totalPrice + $shippingCost;

            // 3. Buat Order
            $order = new Order();
            $order->user_id = $userId;
            $order->address_id = $request->address_id;
            $order->total_price = $totalPrice;
            $order->shipping_cost = $shippingCost;
            $order->grand_total = $grandTotal;
            $order->status = 'pending';
            $order->order_date = now();
            $order->shipping_method = 'Regular Shipping';
            $order->save();

            // 4. Masukkan Items
            foreach ($cartData as $item) {
                if ($item->produk) {
                    OrderItem::create([
                        'order_id' => $order->order_id,
                        'id_produk' => $item->product_id,
                        'quantity' => $item->quantity,
                        'price_at_purchase' => $item->produk->harga
                    ]);
                }
            }

            // 5. DATA PEMBAYARAN & GENERATE KODE VA (PERBAIKAN DISINI)
            $methodName = $request->payment_method; // "Bank Transfer" / "E-Wallet"
            $payMethodModel = PaymentMethod::where('method_name', $methodName)->first();
            $methodId = $payMethodModel ? $payMethodModel->method_id : 1;

            // --- LOGIC GENERATE KODE (SAMA DENGAN WEB) ---
            $transactionCode = 'TRX-' . strtoupper(Str::random(10)); // Default COD
            $bankIdFk = null;
            $ewalletIdFk = null;

            if ($methodName === 'Bank Transfer') {
                $bankIdFk = $request->bank_id; // Dari input body Postman 'bank_id'
                // Format: 8000 + OrderID
                $transactionCode = '8000' . str_pad($order->order_id, 6, '0', STR_PAD_LEFT);
            } 
            elseif ($methodName === 'E-Wallet') {
                $ewalletIdFk = $request->ewallet_id; // Dari input body Postman 'ewallet_id'
                // Format: 0812 + OrderID
                $transactionCode = '0812' . str_pad($order->order_id, 8, '0', STR_PAD_LEFT);
            }

            OrderPayment::create([
                'order_id' => $order->order_id,
                'method_id' => $methodId,
                'amount' => $grandTotal,
                'payment_date' => now(),
                'transaction_status' => 'pending',
                'transaction_code' => $transactionCode, // <--- Kode Angka
                'bank_payment_id_fk' => $bankIdFk,
                'e_wallet_payment_id_fk' => $ewalletIdFk
            ]);

            // 6. Hapus Cart
            CartsItems::where('user_id', $userId)->whereIn('product_id', $selectedIds)->delete();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Order berhasil dibuat!',
                'data' => [
                    'order_id' => $order->order_id,
                    'payment_code' => $transactionCode, // Kirim balik kodenya biar bisa langsung dites
                    'grand_total' => $grandTotal
                ]
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}