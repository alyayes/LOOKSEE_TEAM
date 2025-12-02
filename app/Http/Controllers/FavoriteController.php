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
            'id_produk' => 'required|exists:produk,id_produk'
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

        // PAGINATION FIX
        $favorite_products = Favorite::with('product')
            ->where('user_id', $user->id)
            ->paginate(8); // bebas 8 per halaman

        return view('favorite.favorite', compact('favorite_products', 'user'));
    }
}
