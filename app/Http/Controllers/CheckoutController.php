<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str; 

class CheckoutController extends Controller
{
    
    public function index()
    {
        
        $cart_items = session('cart', []);
        if (empty($cart_items)) {
            $cart_items = [
                [
                'nama_produk' => 'Virly Top',
                'harga' => 56000,
                'stock' => 20,
                'quantity' => 2,
                'gambar_produk' => '4.jpeg',
                ],
                [
                    'nama_produk' => 'Oro Pants',
                    'harga' => 40000,
                    'stock' => 20,
                    'quantity' => 1,
                    'gambar_produk' => 'oro.png',
                ],
            ];
            session(['cart' => $cart_items]);
        }

        $delivery_data = session('temp_delivery_address', [
            'name'        => 'Dior Majorie',
            'phone'       => '0821234567', 
            'address'     => 'Jl. Raya Panjang',
            'city'        => 'Bandung',
            'province'    => 'Jawa Barat',
            'postal_code' => '40000',
        ]);

        $main_payment_methods = [
            ['method_id' => 1, 'method_name' => 'Bank Transfer'],
            ['method_id' => 2, 'method_name' => 'E-Wallet'],
            ['method_id' => 3, 'method_name' => 'Cash on Delivery (COD)'],
        ];
        $bank_options = [
             ['bank_payment_id' => 101, 'bank_name' => 'BCA'], ['bank_payment_id' => 102, 'bank_name' => 'BNI'],
             ['bank_payment_id' => 103, 'bank_name' => 'BRI'], ['bank_payment_id' => 104, 'bank_name' => 'BSI'],
             ['bank_payment_id' => 105, 'bank_name' => 'CIMB Niaga'], ['bank_payment_id' => 106, 'bank_name' => 'Danamon'],
             ['bank_payment_id' => 107, 'bank_name' => 'Mandiri'], ['bank_payment_id' => 108, 'bank_name' => 'Permata'],
             ['bank_payment_id' => 109, 'bank_name' => 'SeaBank'],
        ];
        $e_wallet_options = [
            ['e_wallet_payment_id' => 201, 'ewallet_provider_name' => 'GoPay'],
            ['e_wallet_payment_id' => 202, 'ewallet_provider_name' => 'OVO'],
            ['e_wallet_payment_id' => 203, 'ewallet_provider_name' => 'Dana'],
        ];

        $subtotal = 0;
        foreach ($cart_items as $item) { $subtotal += ($item['harga'] ?? 0) * ($item['quantity'] ?? 1); }
        $shipping_cost = 0; 
        $grand_total = $subtotal + $shipping_cost;

        return view('checkout.checkout', compact(
            'cart_items', 'delivery_data', 'main_payment_methods',
            'bank_options', 'e_wallet_options', 'shipping_cost', 'grand_total'
        ));
    }

    public function processCheckout(Request $request)
    {
         $validatedData = $request->validate([
             'delivery_name' => 'required|string|max:255', 'delivery_phone' => 'nullable|string|max:20', // Phone bisa kosong
             'delivery_address' => 'required|string', 'delivery_city' => 'required|string|max:100',
             'delivery_province' => 'required|string|max:100', 'delivery_postal_code' => 'required|string|max:10',
             'shipping_method' => 'required|string', 'payment_method' => 'required|string',
             'bank_choice' => 'required_if:payment_method,Bank Transfer|nullable|numeric',
             'ewallet_choice' => 'required_if:payment_method,E-Wallet|nullable|numeric',
         ]);


        $order_id = rand(1000, 9999);
        $latest_order = $validatedData;
        $latest_order['order_id'] = $order_id;
        $latest_order['cart_items'] = session('cart', []);
        $subtotal = 0; foreach ($latest_order['cart_items'] as $item) { $subtotal += ($item['harga'] ?? 0) * ($item['quantity'] ?? 1); }
        $shipping_cost = 0;
        $latest_order['total_price'] = $subtotal + $shipping_cost;
        $latest_order['order_date'] = now()->toDateTimeString();
        $latest_order['status'] = ($validatedData['payment_method'] == 'COD') ? 'prepared' : 'pending';
        $latest_order['transaction_code'] = 'TRX-' . Str::upper(Str::random(8)); 

        session(['latest_order_details' => $latest_order]);
        session()->forget(['cart', 'temp_delivery_address']);


        return redirect()->route('payment.details')->with('success', 'Order berhasil dibuat! (Dummy)');
    }


     public function saveTemporaryAddress(Request $request)
     {
         $validated = $request->validate([
             'name' => 'required|string|max:255', 'phone' => 'nullable|string|max:20',
             'address' => 'required|string', 'city' => 'required|string|max:100',
             'province' => 'required|string|max:100', 'postal_code' => 'required|string|max:10',
         ]);
         session(['temp_delivery_address' => $validated]);
         return response()->json(['message' => 'Address updated temporarily.', 'address' => $validated]);
     }
}