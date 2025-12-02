<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\CartsItems;
use App\Models\Favorite;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function show($id)
    {
        $product = Produk::findOrFail($id);
        return view('products.detail', compact('product'));
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'id_produk' => 'required|exists:produk_looksee,id_produk'
        ]);

        // Gunakan session ID untuk user yang tidak login
        $sessionId = session()->getId();

        $cart = CartsItems::firstOrCreate(
            [
                'session_id' => $sessionId,
                'id_produk' => $request->id_produk
            ],
            ['quantity' => 0]
        );

        $cart->increment('quantity');

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil dimasukkan ke keranjang!'
        ]);
    }

    public function addToFavorite(Request $request)
    {
        $request->validate([
            'id_produk' => 'required|exists:produk_looksee,id_produk'
        ]);

        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Silakan login terlebih dahulu.'
            ], 401);
        }

        $userId = Auth::id();
        $productId = $request->id_produk;

        $favorite = Favorite::where('user_id', $userId)
            ->where('id_produk', $productId)
            ->first();

        if ($favorite) {
            $favorite->delete();

            return response()->json([
                'success' => true,
                'status' => 'removed',
                'message' => 'Produk dihapus dari favorit.'
            ]);
        }

        Favorite::create([
            'user_id' => $userId,
            'id_produk' => $productId
        ]);

        return response()->json([
            'success' => true,
            'status' => 'added',
            'message' => 'Produk ditambahkan ke favorit.'
        ]);
    }
}
