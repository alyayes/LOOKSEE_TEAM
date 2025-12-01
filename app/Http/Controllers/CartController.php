<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CartsItems;
use App\Models\Produk;

class CartController extends Controller
{
    // MENAMPILKAN KERANJANG (GLOBAL)
    public function index()
    {
        // Ambil semua data keranjang (tanpa filter user, biar semua bisa lihat)
        $data_cart = CartsItems::with('produk')->get();
        
        $cart_items = [];
        $total_selected_price = 0;

        foreach ($data_cart as $item) {
            if ($item->produk) {
                $total_selected_price += $item->produk->harga * $item->quantity;

                $cart_items[$item->id_produk] = [
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

    // FUNGSI TAMBAH KE KERANJANG (YANG DIPERBAIKI)
    public function addToCart($id_produk)
    {
        // 1. Cek apakah produk dengan ID ini ada di database?
        // (Supaya tidak error Foreign Key jika ID salah)
        $produkExists = Produk::where('id_produk', $id_produk)->exists();
        
        if (!$produkExists) {
            return response()->json([
                'status' => 'error',
                'message' => 'Produk ID ' . $id_produk . ' tidak ditemukan/sudah dihapus!'
            ], 404);
        }

        // 2. Set User ID Dummy (Wajib ada biar database gak nolak)
        $userId = 33; 

        // 3. Cek apakah barang sudah ada di keranjang
        $cek = CartsItems::where('id_produk', $id_produk)->first();

        if ($cek) {
            $cek->increment('quantity');
        } else {
            CartsItems::create([
                'user_id'   => $userId,
                'id_produk' => $id_produk,
                'quantity'  => 1
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil masuk keranjang!'
        ]);
    }

    public function update(Request $req)
    {
        CartsItems::where('id_produk', $req->id_produk)
                  ->update(['quantity' => $req->quantity]);

        return redirect('/cart');
    }

    public function delete(Request $req)
    {
        CartsItems::where('id_produk', $req->id_produk)
                  ->delete();

        return redirect('/cart');
    }
}