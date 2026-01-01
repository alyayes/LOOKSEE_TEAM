<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CartsItems;
use App\Models\UserAddress;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderPayment;
use App\Models\PaymentMethod;
use App\Models\BankTransferDetail;
use App\Models\EwalletTransferDetail;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();
        $selectedIdsString = $request->query('selected_products');

        
        if (!$selectedIdsString) {
            return redirect()->route('cart')->with('error', 'Pilih produk dulu.');
        }

        $selectedIds = explode(',', $selectedIdsString);
        // Ambil Item Cart
        $cartItems = CartsItems::where('user_id', $userId)
                    ->whereIn('product_id', $selectedIds)
                    ->with('produk')
                    ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Produk tidak ditemukan.');
        }

        // Hitung Total
        $totalPrice = 0;
        $itemsFormatted = [];
        foreach($cartItems as $item) {
            if($item->produk) {
                $itemsFormatted[$item->product_id] = [
                    'nama_produk' => $item->produk->nama_produk,
                    'gambar_produk' => $item->produk->gambar_produk,
                    'harga' => $item->produk->harga,
                    'quantity' => $item->quantity
                ];
                $totalPrice += ($item->produk->harga * $item->quantity);
            }
        }

        $shippingCost = 20000;
        $grandTotal = $totalPrice + $shippingCost;

        // Data Pendukung Tampilan
        $addresses = UserAddress::where('user_id', $userId)->get();
        $defaultAddr = $addresses->where('is_default', 1)->first() ?? $addresses->first();
        
        $deliveryData = [
            'name' => $defaultAddr->receiver_name ?? Auth::user()->name,
            'phone' => $defaultAddr->phone_number ?? '-',
            'address' => $defaultAddr->full_address ?? 'Belum ada alamat utama',
            'city' => $defaultAddr->city ?? '',
            'district' => $defaultAddr->district ?? '',
            'province' => $defaultAddr->province ?? '',
            'postal_code' => $defaultAddr->postal_code ?? ''
        ];

        // Opsi Pembayaran
        $paymentMethods = PaymentMethod::all(); 
        $banks = BankTransferDetail::all();
        $ewallets = EwalletTransferDetail::all();

        return view('checkout.checkout', [
            'cart_items' => $itemsFormatted,
            'grand_total' => $grandTotal,
            'shipping_cost' => $shippingCost,
            'delivery_data' => $deliveryData,
            'all_addresses' => $addresses,
            'main_payment_methods' => $paymentMethods,
            'bank_options' => $banks,
            'e_wallet_options' => $ewallets,
            'selectedIdsString' => $selectedIdsString
        ]);
    }

    public function processCheckout(Request $request)
    {
        $userId = Auth::id();
        
        $request->validate([
            'selected_products_ids' => 'required',
            'payment_method' => 'required',
            'bank_choice' => 'required_if:payment_method,Bank Transfer',
            'ewallet_choice' => 'required_if:payment_method,E-Wallet',
        ]);

        $address = UserAddress::where('user_id', $userId)->where('is_default', 1)->first();
        if(!$address) $address = UserAddress::where('user_id', $userId)->first();

        if(!$address) return back()->with('error', 'Mohon isi alamat pengiriman terlebih dahulu.');

        DB::beginTransaction();
        try {
            $selectedIds = explode(',', $request->selected_products_ids);
            
            $cartItems = CartsItems::where('user_id', $userId)->whereIn('product_id', $selectedIds)->with('produk')->get();
            $subtotal = 0;
            foreach ($cartItems as $item) {
                if ($item->produk) $subtotal += $item->produk->harga * $item->quantity;
            }
            $shippingCost = 20000;
            $grandTotal = $subtotal + $shippingCost;

            // 1. Buat Order
            $order = Order::create([
                'user_id' => $userId,
                'address_id' => $address->id,
                'total_price' => $subtotal,
                'shipping_cost' => $shippingCost,
                'grand_total' => $grandTotal,
                'status' => 'pending',
                'order_date' => now(),
                'shipping_method' => $request->shipping_method ?? 'Regular Shipping'
            ]);

            // 2. Masukkan Item & KURANGI STOK
            foreach ($cartItems as $item) {
                if ($item->produk) {
                    
                    // --- TAMBAHAN BARU: KURANGI STOK ---
                    // Ini artinya: kolom 'stock' dikurangi sebanyak 'quantity' yang dibeli
                    $item->produk->decrement('stock', $item->quantity); 
                    // ------------------------------------

                    OrderItem::create([
                        'order_id' => $order->order_id,
                        'id_produk' => $item->product_id,
                        'quantity' => $item->quantity,
                        'price_at_purchase' => $item->produk->harga
                    ]);
                }
            }

            // 3. PROSES KODE BAYAR (HARDCODE DISINI BIAR PASTI MUNCUL)
            $methodName = $request->payment_method;
            
            $payMethod = PaymentMethod::where('method_name', $methodName)->first();
            $methodId = $payMethod ? $payMethod->method_id : 1;

            // Default
            $transactionCode = 'TRX-' . strtoupper(Str::random(10));
            $bankIdFk = null;
            $ewalletIdFk = null;

            // --- LOGIC HARDCODE NOMOR REKENING ---
            if ($methodName === 'Bank Transfer') {
                $bankIdFk = $request->bank_choice;
                
                // Cek Bank Apa yang Dipilih User
                $bankDetail = BankTransferDetail::find($bankIdFk);
                if ($bankDetail) {
                    $namaBank = strtoupper($bankDetail->bank_name);
                    
                    // JURUS TEMBAK LANGSUNG:
                    if (str_contains($namaBank, 'MANDIRI')) {
                        $transactionCode = '1320028954056'; // <--- MANDIRI KAMU
                    } elseif (str_contains($namaBank, 'SEABANK')) {
                        $transactionCode = '901566333248'; // <--- SEABANK KAMU
                    } else {
                        // Jaga-jaga kalau ada bank lain, generate random
                        $transactionCode = '8000' . str_pad($order->order_id, 6, '0', STR_PAD_LEFT);
                    }
                }
            } 
            elseif ($methodName === 'E-Wallet') {
                $ewalletIdFk = $request->ewallet_choice;
                // E-WALLET SEMUA SAMA
                $transactionCode = '082127222144'; // <--- NOMOR HP KAMU
            }

            // Simpan Payment
            OrderPayment::create([
                'order_id' => $order->order_id,
                'method_id' => $methodId,
                'amount' => $grandTotal,
                'payment_date' => now(),
                'transaction_status' => 'pending',
                'transaction_code' => $transactionCode, // <--- Kode ini yang bakal muncul di view
                'bank_payment_id_fk' => $bankIdFk,
                'e_wallet_payment_id_fk' => $ewalletIdFk
            ]);

            // 4. Hapus Cart
            CartsItems::where('user_id', $userId)->whereIn('product_id', $selectedIds)->delete();

            DB::commit();

            return redirect()->route('payment.details', ['order_id' => $order->order_id])
                             ->with('success', 'Order berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses order: ' . $e->getMessage());
        }
    }

    // Fungsi Tambahan Address
    public function addAddress(Request $request) {
        $userId = Auth::id();
        $request->validate([
            'receiver_name' => 'required',
            'phone_number' => 'required',
            'full_address' => 'required',
            'city' => 'required',
            'province' => 'required',
            'postal_code' => 'required',
        ]);

        // Cek apakah ini alamat pertama?
        $count = UserAddress::where('user_id', $userId)->count();
        $isDefault = $count == 0 ? 1 : 0;

        UserAddress::create([
            'user_id' => $userId,
            'receiver_name' => $request->receiver_name,
            'phone_number' => $request->phone_number,
            'full_address' => $request->full_address,
            'district' => $request->district,
            'city' => $request->city,
            'province' => $request->province,
            'postal_code' => $request->postal_code,
            'is_default' => $isDefault
        ]);

        return back()->with('success', 'Alamat berhasil ditambahkan!');
    }

    public function deleteAddress($id) {
        UserAddress::where('id', $id)->where('user_id', Auth::id())->delete();
        return back()->with('success', 'Alamat dihapus.');
    }

    public function updateAddress(Request $request, $id) {
        $addr = UserAddress::where('id', $id)->where('user_id', Auth::id())->first();
        if($addr) {
            $addr->update($request->except(['_token', '_method']));
            return back()->with('success', 'Alamat diperbarui.');
        }
        return back()->with('error', 'Alamat tidak ditemukan.');
    }
}