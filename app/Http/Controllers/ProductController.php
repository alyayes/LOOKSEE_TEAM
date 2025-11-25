<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Sumber data dummy untuk semua produk.
     */
    private function getDummyProducts()
    {
        return [
            'A01' => ['id_produk' => 'A01', 'nama_produk' => 'Ribonia', 'deskripsi' => 'Ribonia Shirt Katun Poly Lembut', 'harga' => 30000, 'gambar_produk' => 'ribonia.png'], // Contoh dari gambar
            'A02' => ['id_produk' => 'A02', 'nama_produk' => 'Classic Leather Boots', 'deskripsi' => 'Handcrafted leather boots with a timeless design.', 'harga' => 1200000, 'gambar_produk' => 'black hijab.jpg'],
            'B01' => ['id_produk' => 'B01', 'nama_produk' => 'Vintage Denim Jacket', 'deskripsi' => 'A stylish vintage denim jacket with a classic fit.', 'harga' => 550000, 'gambar_produk' => 'jeans jaket.jpeg'],
            'C01' => ['id_produk' => 'C01', 'nama_produk' => 'Gia Jeans Highwaist', 'deskripsi' => 'Comfortable and stylish high-waist jeans.', 'harga' => 349000, 'gambar_produk' => 'highwaisted denim.jpg'],
        ];
    }

    /**
     * Menampilkan halaman detail untuk satu produk.
     */
    public function show($id)
    {
        $all_products = $this->getDummyProducts();
        $product = $all_products[$id] ?? null;

        if (!$product) {
            abort(404, 'Product not found.');
        }

        return view('products.detail', compact('product'));
    }

    /**
     * MENAMBAHKAN PRODUK KE KERANJANG (DUMMY AJAX HANDLER).
     */
    public function addToCart(Request $request)
    {
        $validated = $request->validate(['id_produk' => 'required']);
        // Logika dummy: Cukup kirim respons sukses
        return response()->json([
            'status' => 'success',
            'message' => 'Product added to cart!'
        ]);
    }

    /**
     * Menambahkan produk ke favorit (dummy AJAX handler).
     */
    public function addToFavorite(Request $request)
    {
         $validated = $request->validate(['id_produk' => 'required']);
        // Logika dummy: Kirim status 'added' atau 'removed' secara acak
        $status = ['added', 'removed'][array_rand(['added', 'removed'])];
         return response()->json([
             'success' => true,
             'status' => $status,
             'message' => 'Product ' . $status . ' to/from favorites!'
         ]);
    }
}

