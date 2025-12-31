<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Post; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $favorite_products = Favorite::with('product')
            ->where('user_id', $user->user_id) 
            ->get();

        $liked_posts = Post::whereHas('likes', function($query) use ($user) {
                $query->where('user_id', $user->user_id);
            })
            ->with('user')
            ->withCount('likes as total_likes')
            ->get();

        return view('favorite.favorite', compact('favorite_products', 'liked_posts', 'user'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_produk' => 'required'
        ]);

        $user = Auth::user();

        $existing = Favorite::where('user_id', $user->user_id)
            ->where('id_produk', $request->id_produk)
            ->first();

        if ($existing) {
            $existing->delete();
            return response()->json(['status' => 'removed', 'message' => 'Removed from favorites']);
        }

        Favorite::create([
            'user_id' => $user->user_id,
            'id_produk' => $request->id_produk
        ]);

        return response()->json(['status' => 'added', 'message' => 'Added to favorites']);
    }

    public function deleteFavorite(Request $request)
    {
        Favorite::where('user_id', Auth::user()->user_id)
                ->where('id_produk', $request->id_produk)
                ->delete();

        return response()->json(['status' => 'removed', 'message' => 'Product successfully removed']);
    }

    public function addToCart(Request $request)
    {
        return response()->json(['status' => 'success', 'message' => 'Product added to cart!']);
    }
}