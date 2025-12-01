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

        $existing = Favorite::where('id_user', $user->id)
                            ->where('id_produk', $request->id_produk)
                            ->first();

        if ($existing) {
            $existing->delete();
            return response()->json(['status' => 'removed']);
        }

        Favorite::create([
            'id_user' => $user->id,
            'id_produk' => $request->id_produk
        ]);

        return response()->json(['status' => 'added']);
    }

    public function index()
    {
        $user = Auth::user();
        $favorite_products = Favorite::with('produk')
            ->where('id_user', $user->id)
            ->get();

        return view('favorite.favorite', compact('favorite_products'));
    }
}
