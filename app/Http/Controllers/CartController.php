<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends Controller {
    public function index() {

        $cart_items = [
            1 => [
                'nama_produk' => 'Virly Top',
                'harga' => 56000,
                'stock' => 20,
                'quantity' => 2,
                'gambar_produk' => '4.jpeg',
            ],
            2 => [
                'nama_produk' => 'Oro Pants',
                'harga' => 40000,
                'stock' => 20,
                'quantity' => 1,
                'gambar_produk' => 'oro.png',
            ],
        ];

        $total_selected_price = array_sum(array_map(function ($item) {
            return $item['harga'] * $item['quantity'];
        }, $cart_items));

        $total_price = $total_selected_price;
        $total_products = array_sum(array_column($cart_items, 'quantity'));

        return view('cart.cart', compact('cart_items', 'total_price', 'total_products'));

    }

    public function update(Request $request) {

        return response()->json(['status' => 'success', 'message' => 'Quantity updated (dummy).']);
    }

    public function delete(Request $request) {

        return response()->json(['status' => 'success', 'message' => 'Item deleted (dummy).']);
    }

    public function checkout(Request $request) {

        return redirect()->route('cart')->with('success', 'Checkout berhasil (dummy)!');
    }
}