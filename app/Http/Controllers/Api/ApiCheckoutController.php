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
    /**
     * 1. AMBIL DATA PENDUKUNG CHECKOUT
     * Mengambil daftar alamat, item keranjang yang dipilih, dan opsi pembayaran.
     */
    public function getCheckoutData(Request $request)
    {
        $userId = Auth::id();
        $selectedIdsString = $request->query('selected_products');

        if (!$selectedIdsString) {
            return response()->json(['status' => 'error', 'message' => 'Pilih produk terlebih dahulu.'], 400);
        }

        $selectedIds = explode(',', $selectedIdsString);

        // Ambil Data Item Keranjang
        $cartItems = CartsItems::where('user_id', $userId)
                    ->whereIn('product_id', $selectedIds)
                    ->with('produk')
                    ->get();

        // Format data produk dan hitung total
        $totalPrice = 0;
        $itemsFormatted = $cartItems->map(function($item) use (&$totalPrice) {
            $subtotal = $item->produk->harga * $item->quantity;
            $totalPrice += $subtotal;
            return [
                'product_id' => $item->product_id,
                'nama_produk' => $item->produk->nama_produk,
                'gambar_produk' => asset('assets/images/produk-looksee/' . $item->produk->gambar_produk),
                'harga' => $item->produk->harga,
                'quantity' => $item->quantity,
                'subtotal' => $subtotal
            ];
        });

        $shippingCost = 20000;
        
        // Data Alamat
        $addresses = UserAddress::where('user_id', $userId)->get();

        // Opsi Pembayaran (Bank & E-Wallet)
        $paymentMethods = PaymentMethod::all(); 
        $banks = BankTransferDetail::all();
        $ewallets = EwalletTransferDetail::all();

        return response()->json([
            'status' => 'success',
            'data' => [
                'items' => $itemsFormatted,
                'summary' => [
                    'total_price' => $totalPrice,
                    'shipping_cost' => $shippingCost,
                    'grand_total' => $totalPrice + $shippingCost,
                ],
                'addresses' => $addresses,
                'payment_options' => [
                    'methods' => $paymentMethods, // "Bank Transfer", "E-Wallet", "COD"
                    'banks' => $banks,
                    'ewallets' => $ewallets
                ]
            ]
        ]);
    }

    /**
     * 2. PROSES CHECKOUT
     * Membuat order, mengurangi stok, dan generate kode pembayaran.
     */
    public function processCheckout(Request $request)
    {
        $userId = Auth::id();
        
        $request->validate([
            'selected_products' => 'required', // String ID dipisah koma
            'address_id'        => 'required|exists:user_addresses,id',
            'payment_method'    => 'required', 
            'bank_id'           => 'required_if:payment_method,Bank Transfer',
            'ewallet_id'        => 'required_if:payment_method,E-Wallet',
        ]);

        $selectedIds = explode(',', $request->selected_products);

        DB::beginTransaction();
        try {
            // Hitung Ulang Total & Validasi Keranjang
            $cartItems = CartsItems::where('user_id', $userId)
                        ->whereIn('product_id', $selectedIds)
                        ->with('produk')
                        ->get();
            
            if ($cartItems->isEmpty()) throw new \Exception("Produk keranjang tidak ditemukan");

            $totalPrice = 0;
            foreach ($cartItems as $item) {
                if ($item->produk) $totalPrice += $item->produk->harga * $item->quantity;
            }
            
            $shippingCost = 20000;
            $grandTotal = $totalPrice + $shippingCost;

            // 1. Buat Order
            $order = Order::create([
                'user_id' => $userId,
                'address_id' => $request->address_id,
                'total_price' => $totalPrice,
                'shipping_cost' => $shippingCost,
                'grand_total' => $grandTotal,
                'status' => 'pending',
                'order_date' => now(),
                'shipping_method' => 'Regular Shipping'
            ]);

            // 2. Masukkan Item & Kurangi Stok (Decrement)
            foreach ($cartItems as $item) {
                if ($item->produk) {
                    // Cek stok cukup atau tidak
                    if ($item->produk->stock < $item->quantity) {
                        throw new \Exception("Stok produk {$item->produk->nama_produk} tidak cukup.");
                    }

                    // Kurangi stok produk
                    $item->produk->decrement('stock', $item->quantity);

                    OrderItem::create([
                        'order_id' => $order->order_id,
                        'id_produk' => $item->product_id,
                        'quantity' => $item->quantity,
                        'price_at_purchase' => $item->produk->harga
                    ]);
                }
            }

            // 3. Logika Generate Kode VA / Rekening (Sesuai Logic Web)
            $methodName = $request->payment_method;
            $payMethodModel = PaymentMethod::where('method_name', $methodName)->first();
            $methodId = $payMethodModel ? $payMethodModel->method_id : 1;

            $transactionCode = 'TRX-' . strtoupper(Str::random(10)); // Default COD
            $bankIdFk = null;
            $ewalletIdFk = null;

            if ($methodName === 'Bank Transfer') {
                $bankIdFk = $request->bank_id;
                $bankDetail = BankTransferDetail::find($bankIdFk);
                if ($bankDetail) {
                    $namaBank = strtoupper($bankDetail->bank_name);
                    // Tembak langsung nomor rekening sesuai pilihan
                    if (str_contains($namaBank, 'MANDIRI')) $transactionCode = '1320028954056';
                    elseif (str_contains($namaBank, 'SEABANK')) $transactionCode = '901566333248';
                    else $transactionCode = '8000' . str_pad($order->order_id, 6, '0', STR_PAD_LEFT);
                }
            } 
            elseif ($methodName === 'E-Wallet') {
                $ewalletIdFk = $request->ewallet_id;
                $transactionCode = '082127222144'; // Nomor HP Admin
            }

            // 4. Simpan Record Pembayaran
            OrderPayment::create([
                'order_id' => $order->order_id,
                'method_id' => $methodId,
                'amount' => $grandTotal,
                'payment_date' => now(),
                'transaction_status' => 'pending',
                'transaction_code' => $transactionCode,
                'bank_payment_id_fk' => $bankIdFk,
                'e_wallet_payment_id_fk' => $ewalletIdFk
            ]);

            // 5. Bersihkan Item dari Keranjang
            CartsItems::where('user_id', $userId)->whereIn('product_id', $selectedIds)->delete();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Checkout berhasil diproses!',
                'data' => [
                    'order_id' => $order->order_id,
                    'payment_code' => $transactionCode,
                    'grand_total' => $grandTotal
                ]
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * 3. MANAJEMEN ALAMAT (API)
     */
    public function addAddress(Request $request) {
        $userId = Auth::id();
        $request->validate([
            'receiver_name' => 'required',
            'phone_number'  => 'required',
            'full_address'  => 'required',
            'city'          => 'required',
            'province'      => 'required',
            'postal_code'   => 'required',
        ]);

        $count = UserAddress::where('user_id', $userId)->count();
        $isDefault = ($count == 0) ? 1 : 0;

        $address = UserAddress::create(array_merge($request->all(), [
            'user_id' => $userId,
            'is_default' => $isDefault
        ]));

        return response()->json(['status' => 'success', 'data' => $address]);
    }
}