<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Cart;
use App\Models\Product;
use App\Models\UsersAdmin;
use Illuminate\Support\Facades\Storage;

class CartController extends Controller {
    public function index() 
    {
        $userId = UsersAdmin::id();

        $cartData = Cart::where('user_id', $userId)
                        ->with('produk')
                        ->get();
        $cart_items = [];

        foreach ($cartData as $item) {
            if ($item->produk) {
                $cart_items[$item->id_produk] = [
                    'nama_produk'   => $item->produk->nama_produk,
                    'harga'         => $item->produk->harga,
                    'stock'         => $item->produk->stock,
                    'quantity'      => $item->quantity,
                    'gambar_produk' => $item->produk->gambar_produk
                ];
            }
        }

        $total_selected_price = 0;
        foreach ($cart_items as $product) {
            $total_selected_price += $product['harga'] * $product['quantity'];
        }

        $total_products = count($cart_items);

        return view('cart.cart', compact('cart_items', 'total_selected_price', 'total_products'));
    }


    public function update(Request $request) 
    {
        $request->validate([
            'id_produk' => 'required',
            'quantity'  => 'required|integer|min:1',
        ]);

        $cartItem = Cart::where('user_id', Auth::id())
                        ->where('id_produk', $request->id_produk)
                        ->first();

        if ($cartItem) {
            $cartItem->update([
                'quantity' => $request->quantity
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Quantity updated successfully'
        ]);
    }

    public function delete(Request $request) 
    {
        $request->validate([
            'id_produk' => 'required'
        ]);

        Cart::where('user_id', Auth::id())
            ->where('id_produk', $request->id_produk)
            ->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Item removed successfully'
        ]);
    }

    public function checkout(Request $request) 
    {
        return redirect()->route('cart');
    }
}