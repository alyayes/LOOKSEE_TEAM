<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\CartsItems;
use App\Models\UserAddress;
use App\Models\PaymentMethod;
use App\Models\EwalletTransferDetail;
use App\Models\BankTransferDetail;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderPayment;

class CheckoutController extends Controller
{
    private $userId = 33; // Hardcode User ID

    public function index(Request $request)
    {
        $selectedIdsString = $request->query('selected_products');
        
        if (!$selectedIdsString) {
            return redirect()->route('cart')->with('error', 'Silakan pilih produk terlebih dahulu.');
        }

        $selectedIds = array_filter(explode(',', $selectedIdsString));

        $cartData = CartsItems::with('produk')
                              ->whereIn('product_id', $selectedIds)
                              ->get();

        $cart_items = [];
        
        // Grouping Data
        foreach ($cartData as $item) {
            if ($item->produk) {
                // Logic Overwrite/Merge Quantity
                if (isset($cart_items[$item->product_id])) {
                    $cart_items[$item->product_id]['quantity'] += $item->quantity;
                } else {
                    $cart_items[$item->product_id] = [
                        'product_id'    => $item->product_id,
                        'nama_produk'   => $item->produk->nama_produk,
                        'harga'         => $item->produk->harga,
                        'gambar_produk' => $item->produk->gambar_produk,
                        'quantity'      => $item->quantity,
                        'stock'         => $item->produk->stock,
                    ];
                }
            }
        }

        $total_selected_price = 0;
        foreach ($cart_items as $item) {
            $total_selected_price += $item['harga'] * $item['quantity'];
        }

        $shipping_cost = 20000;
        $grand_total = $total_selected_price + $shipping_cost;

        $all_addresses = UserAddress::where('user_id', $this->userId)->orderBy('is_default', 'desc')->get();
        $address = $all_addresses->first();

        $delivery_data = [
            'id'          => $address->id ?? null,
            'name'        => $address->receiver_name ?? 'Guest',
            'phone'       => $address->phone_number ?? '-',
            'address'     => $address->full_address ?? 'Belum ada alamat',
            'city'        => $address->city ?? '-',
            'district'    => $address->district ?? '-',
            'province'    => $address->province ?? '-',
            'postal_code' => $address->postal_code ?? '-',
        ];

        $main_payment_methods = PaymentMethod::all();
        $e_wallet_options     = EwalletTransferDetail::all();
        $bank_options         = BankTransferDetail::all(); 

        return view('checkout.checkout', compact(
            'cart_items',
            'total_selected_price',
            'shipping_cost',
            'grand_total',
            'delivery_data',
            'all_addresses',
            'main_payment_methods',
            'e_wallet_options',
            'bank_options',
            'selectedIdsString'
        ));
    }

public function processCheckout(Request $request)
    {
        // 1. Validasi Input
        $selectedIdsString = $request->input('selected_products_ids');

        // Fallback: Jika input hidden kosong, coba ambil dari semua cart (Global Mode)
        // Ini untuk mencegah error jika user refresh halaman checkout
        if (!$selectedIdsString) {
             $cartCheck = CartsItems::first();
             if($cartCheck) {
                 // Ambil semua ID produk di cart sebagai fallback
                 $allIds = CartsItems::pluck('product_id')->toArray();
                 $selectedIds = $allIds;
             } else {
                 return redirect()->route('cart')->with('error', 'Keranjang kosong.');
             }
        } else {
            $selectedIds = explode(',', $selectedIdsString);
        }

        // 2. Ambil Data Cart
        $cartData = CartsItems::with('produk')->whereIn('product_id', $selectedIds)->get();

        if ($cartData->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Produk tidak ditemukan.');
        }

        // 3. Hitung Total
        $cart_items_unique = [];
        foreach ($cartData as $item) {
            if ($item->produk) {
                if(isset($cart_items_unique[$item->product_id])) {
                    $cart_items_unique[$item->product_id]['quantity'] += $item->quantity;
                } else {
                    $cart_items_unique[$item->product_id] = [
                        'product_id' => $item->product_id,
                        'quantity'   => $item->quantity,
                        'price'      => $item->produk->harga
                    ];
                }
            }
        }

        $total_price = 0;
        foreach ($cart_items_unique as $item) {
            $total_price += $item['price'] * $item['quantity'];
        }
        $shipping_cost = 20000;
        $grand_total = $total_price + $shipping_cost;

        // 4. Simpan Transaksi
        DB::beginTransaction();
        try {
            $order = new Order();
            $order->user_id = $this->userId;
            
            // Ambil Address ID (karena DB kamu cuma terima ID)
            $addr = UserAddress::where('user_id', $this->userId)->orderBy('is_default', 'desc')->first();
            $order->address_id = $addr ? $addr->id : null;
            
            $order->total_price     = $total_price;
            $order->shipping_cost   = $shipping_cost;
            $order->grand_total     = $grand_total;
            $order->status          = 'pending';
            $order->order_date      = now();
            $order->shipping_method = $request->shipping_method;
            $order->save();

            foreach ($cart_items_unique as $item) {
                OrderItem::create([
                    'order_id'          => $order->order_id,
                    'id_produk'         => $item['product_id'],
                    'quantity'          => $item['quantity'],
                    'price_at_purchase' => $item['price']
                ]);
            }

            // Payment
            $paymentMethod = PaymentMethod::where('method_name', $request->payment_method)->first();
            $methodId = $paymentMethod ? $paymentMethod->method_id : 1;

            OrderPayment::create([
                'order_id'               => $order->order_id,
                'method_id'              => $methodId,
                'amount'                 => $grand_total,
                'payment_date'           => now(),
                'transaction_status'     => 'pending',
                'transaction_code'       => 'TRX-' . strtoupper(uniqid()),
                'bank_payment_id_fk'     => $request->bank_choice,
                'e_wallet_payment_id_fk' => $request->ewallet_choice
            ]);

            // Hapus Cart
            CartsItems::whereIn('product_id', $selectedIds)->delete();

            DB::commit();
            return redirect()->route('orders.list')->with('success', 'Order berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    

    // ... (Fungsi addAddress, updateAddress, deleteAddress biarkan sama) ...
    public function addAddress(Request $request) {
        $request->validate([
            'receiver_name' => 'required', 'phone_number' => 'required', 'full_address' => 'required',
            'province' => 'required', 'city' => 'required', 'postal_code' => 'required',
        ]);
        $isFirst = !UserAddress::exists();
        UserAddress::create([
            'user_id' => $this->userId, 'receiver_name' => $request->receiver_name, 'phone_number' => $request->phone_number,
            'full_address' => $request->full_address, 'province' => $request->province, 'city' => $request->city,
            'district' => $request->district ?? '-', 'postal_code' => $request->postal_code, 'is_default' => $isFirst ? true : false,
        ]);
        return redirect()->back()->with('success', 'Alamat baru berhasil ditambahkan!');
    }
    
    public function updateAddress(Request $request, $id) { UserAddress::findOrFail($id)->update($request->all()); return redirect()->back(); }
    public function deleteAddress($id) { UserAddress::findOrFail($id)->delete(); return redirect()->back(); }
}