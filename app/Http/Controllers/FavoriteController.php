<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'id_produk' => 'required|exists:produk_looksee,id_produk'
        ]);

        $user = Auth::user();

        $existing = Favorite::where('user_id', $user->id)
            ->where('id_produk', $request->id_produk)
            ->first();

        if ($existing) {
            $existing->delete();
            return response()->json(['status' => 'removed']);
        }

        Favorite::create([
            'user_id' => $user->id,
            'id_produk' => $request->id_produk
        ]);

        return response()->json(['status' => 'added']);
    }

    public function index()
    {
        $user = Auth::user();

        // FAVORITE PRODUCTS
        $favorite_products = Favorite::with('product')
            ->where('user_id', $user->id)
            ->paginate(8);

        // STYLE FAVORITES (sementara kosong)
        $liked_posts = collect(); // FIX ERROR Undefined variable

        return view('favorite.favorite', [
            'favorite_products' => $favorite_products,
            'liked_posts' => $liked_posts,
            'user' => $user
        ]);
    }

}
