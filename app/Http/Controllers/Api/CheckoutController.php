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

class ApiCheckoutController extends Controller
{
    /**
     * 1. GET CHECKOUT SUMMARY (Persiapan Data sebelum Bayar)
     * Method: GET
     * URL: /api/checkout/summary?selected_products=1,2,3
     */
    public function getCheckoutData(Request $request)
    {
        $userId = Auth::id();
        if (!$userId) return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);

        // Ambil parameter selected_products dari URL
        $selectedIdsString = $request->query('selected_products');
        
        if (!$selectedIdsString) {
            return response()->json(['status' => 'error', 'message' => 'Tidak ada produk dipilih'], 400);
        }

        $selectedIds = explode(',', $selectedIdsString);

        // Ambil items dari cart berdasarkan ID yang dipilih
        $cartData = CartsItems::with('produk')
                    ->where('user_id', $userId)
                    ->whereIn('product_id', $selectedIds)
                    ->get();

        if ($cartData->isEmpty()) {
            return response()->json(['status' => 'error', 'message' => 'Produk cart tidak valid atau kosong'], 404);
        }

        // Hitung Total & Format Data
        $items = [];
        $totalPrice = 0;

        foreach($cartData as $row) {
            if($row->produk) {
                $sub = $row->produk->harga * $row->quantity;
                $totalPrice += $sub;
                
                $items[] = [
                    'product_id' => $row->product_id,
                    'nama' => $row->produk->nama_produk,
                    'img' => asset('assets/images/produk-looksee/'.$row->produk->gambar_produk),
                    'price' => $row->produk->harga,
                    'qty' => $row->quantity,
                    'subtotal' => $sub
                ];
            }
        }

        $shippingCost = 20000; // Biaya ongkir flat (sesuaikan logika kamu)
        $grandTotal = $totalPrice + $shippingCost;

        // Ambil Data Pendukung (Alamat & Payment Options)
        $addresses = UserAddress::where('user_id', $userId)->orderBy('is_default', 'desc')->get();
        
        $paymentMethods = PaymentMethod::all(); // Bank Transfer, E-Wallet, COD
        $banks = BankTransferDetail::all();     // List Bank (BCA, Mandiri, dll)
        $ewallets = EwalletTransferDetail::all(); // List Ewallet (Gopay, OVO, dll)

        return response()->json([
            'status' => 'success',
            'data' => [
                'items' => $items,
                'summary' => [
                    'subtotal' => $totalPrice,
                    'shipping_cost' => $shippingCost,
                    'grand_total' => $grandTotal
                ],
                'user_addresses' => $addresses,
                'payment_options' => [
                    'methods' => $paymentMethods,
                    'banks' => $banks,
                    'ewallets' => $ewallets
                ]
            ]
        ], 200);
    }

    /**
     * 2. PROCESS CHECKOUT (Klik tombol "Place Order")
     * Method: POST
     * URL: /api/checkout/process
     * Body: selected_products ("1,2"), address_id, payment_method ("Bank Transfer"), bank_id (opsional), ewallet_id (opsional)
     */
    public function processCheckout(Request $request)
    {
        $userId = Auth::id();
        if (!$userId) return response()->json(['message' => 'Unauthorized'], 401);

        // Validasi Input dari Frontend
        $request->validate([
            'selected_products' => 'required', // String "1,2,3"
            'address_id'        => 'required|exists:user_address,id',
            'payment_method'    => 'required', // String nama method, misal "Bank Transfer"
        ]);

        $selectedIds = explode(',', $request->selected_products);

        // Mulai Transaksi Database
        DB::beginTransaction();
        try {
            // 1. Ambil data cart lagi untuk hitung ulang (Security: jangan percaya total harga dari frontend)
            $cartData = CartsItems::with('produk')
                        ->where('user_id', $userId)
                        ->whereIn('product_id', $selectedIds)
                        ->get();
            
            if ($cartData->isEmpty()) throw new \Exception("Produk tidak ditemukan/kosong");

            $totalPrice = 0;
            foreach ($cartData as $item) {
                if ($item->produk) {
                    $totalPrice += $item->produk->harga * $item->quantity;
                    
                    // (Opsional) Cek Stok lagi di sini biar aman
                    if($item->quantity > $item->produk->stock) {
                        throw new \Exception("Stok " . $item->produk->nama_produk . " tidak mencukupi.");
                    }
                }
            }

            $shippingCost = 20000;
            $grandTotal = $totalPrice + $shippingCost;

            // 2. Buat Record Order
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

            // 3. Pindahkan Cart Items ke Order Items
            foreach ($cartData as $item) {
                if ($item->produk) {
                    OrderItem::create([
                        'order_id' => $order->order_id,
                        'id_produk' => $item->product_id,
                        'quantity' => $item->quantity,
                        'price_at_purchase' => $item->produk->harga
                    ]);
                    
                    // (Opsional) Kurangi Stok Produk
                    // $item->produk->decrement('stock', $item->quantity);
                }
            }

            // 4. Simpan Data Pembayaran
            $payMethodModel = PaymentMethod::where('method_name', $request->payment_method)->first();
            $methodId = $payMethodModel ? $payMethodModel->method_id : 1; // Default 1 jika tidak ketemu

            OrderPayment::create([
                'order_id' => $order->order_id,
                'method_id' => $methodId,
                'amount' => $grandTotal,
                'payment_date' => now(),
                'transaction_status' => 'pending',
                'transaction_code' => 'TRX-' . strtoupper(uniqid()),
                'bank_payment_id_fk' => $request->bank_id ?? null,
                'e_wallet_payment_id_fk' => $request->ewallet_id ?? null
            ]);

            // 5. Hapus Item yang dibeli dari Keranjang
            CartsItems::where('user_id', $userId)->whereIn('product_id', $selectedIds)->delete();

            // Commit Transaksi
            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Order berhasil dibuat!',
                'data' => [
                    'order_id' => $order->order_id,
                    'grand_total' => $grandTotal
                ]
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error', 
                'message' => 'Gagal memproses checkout: ' . $e->getMessage()
            ], 500);
        }
    }
}