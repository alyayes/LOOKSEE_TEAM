<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Favorite;
use App\Models\Produk;

class FavoriteController extends Controller
{
    public function index()
    {
        $userId = 1; // sementara, karena kamu bilang jangan pakai Auth dulu

        // Ambil daftar favorite user
        $favorites = Favorite::where('user_id', $userId)->get();

        $favorite_products = [];

        foreach ($favorites as $fav) {

            // Ambil produk dari tabel produk_looksee (MODEL SUDAH BENAR)
            $produk = Produk::where('id_produk', $fav->id_produk)->first();

            if ($produk) {
                $favorite_products[] = [
                    'id_fav'        => $fav->id_fav,
                    'id_produk'     => $produk->id_produk,
                    'nama_produk'   => $produk->nama_produk,
                    'harga'         => $produk->harga,
                    'gambar_produk' => $produk->gambar_produk,
                ];
            }
        }

        return view('favorite.favorite', compact('favorite_products'));
    }

    public function deleteFavorite(Request $request)
    {
        $request->validate(['id_favorite' => 'required|numeric']);

        Favorite::where('id_fav', $request->id_favorite)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product successfully removed!'
        ]);
    }
}
