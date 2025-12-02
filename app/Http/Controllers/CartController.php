<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CartsItems;
use App\Models\Produk;

class CartController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        if (!$userId) return redirect()->route('login');

        $data_cart = CartsItems::with('produk')
            ->where('user_id', $userId)
            ->get();
        
        $cart_items = [];
        $total_selected_price = 0;

        foreach ($data_cart as $item) {
            if ($item->produk) {
                $total_selected_price += $item->produk->harga * $item->quantity;

                $cart_items[$item->product_id] = [
                    'id_produk'     => $item->product_id, 
                    'nama_produk'   => $item->produk->nama_produk,
                    'harga'         => $item->produk->harga,
                    'stock'         => $item->produk->stock,
                    'quantity'      => $item->quantity,
                    'gambar_produk' => $item->produk->gambar_produk
                ];
            }
        }

        $total_products = count($cart_items);

        return view('cart.cart', compact('cart_items', 'total_selected_price', 'total_products'));
    }

    public function addToCart(Request $request)
    {
        $id_produk = $request->input('id_produk');
        $userId = Auth::id();
        
        if (!$userId) {
            return response()->json(['status' => 'error', 'message' => 'Silakan login terlebih dahulu.'], 401);
        }

        $produkExists = Produk::where('id_produk', $id_produk)->exists();
        if (!$produkExists) {
            return response()->json(['status' => 'error', 'message' => 'Produk tidak ditemukan!'], 404);
        }

        $cek = CartsItems::where('user_id', $userId)
                         ->where('product_id', $id_produk) 
                         ->first();

        if ($cek) {
            $cek->increment('quantity');
        } else {
            CartsItems::create([
                'user_id'    => $userId, 
                'product_id' => $id_produk,
                'quantity'   => 1
            ]);
        }

        return response()->json(['status' => 'success', 'message' => 'Berhasil masuk keranjang!']);
    }

    public function update(Request $req)
    {
        $userId = Auth::id();
        
        CartsItems::where('user_id', $userId)
                  ->where('product_id', $req->id_produk) 
                  ->update(['quantity' => $req->quantity]);

        return response()->json(['status' => 'success']);
    }

    public function delete(Request $req)
    {
        $userId = Auth::id();
        
        CartsItems::where('user_id', $userId)
                  ->where('product_id', $req->id_produk) 
                  ->delete();

        return redirect('/cart');
    }
}