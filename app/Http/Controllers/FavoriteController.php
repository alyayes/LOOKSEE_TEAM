<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Post; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    /**
     * Menampilkan halaman favorit (Style & Products)
     */
    public function index()
    {
        // 1. Ambil data user yang sedang login
        $user = Auth::user();

        // 2. Ambil Produk Favorit menggunakan relasi 'product' yang ada di Model Favorite
        // Kita menggunakan user_id dari objek $user yang sedang login
        $favorite_products = Favorite::with('product')
            ->where('user_id', $user->user_id) 
            ->get();

        // 3. Ambil Postingan yang di-like oleh user (Tab Style)
        // Mengambil post dimana user saat ini memberikan 'Like'
        $liked_posts = Post::whereHas('likes', function($query) use ($user) {
                $query->where('user_id', $user->user_id);
            })
            ->with('user')
            ->withCount('likes as total_likes')
            ->get();

        // 4. Kirim data ke view favorite.blade.php
        return view('favorite.favorite', compact('favorite_products', 'liked_posts', 'user'));
    }

    /**
     * Menambah atau Menghapus Produk dari Favorit (Toggle)
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_produk' => 'required'
        ]);

        $user = Auth::user();

        // Cek apakah produk sudah ada di daftar favorit user
        $existing = Favorite::where('user_id', $user->user_id)
            ->where('id_produk', $request->id_produk)
            ->first();

        if ($existing) {
            // Jika ada, maka dihapus (Logic Toggle)
            $existing->delete();
            return response()->json(['status' => 'removed', 'message' => 'Removed from favorites']);
        }

        // Jika belum ada, maka ditambah
        Favorite::create([
            'user_id' => $user->user_id,
            'id_produk' => $request->id_produk
        ]);

        return response()->json(['status' => 'added', 'message' => 'Added to favorites']);
    }

    /**
     * Menghapus produk dari halaman favorit secara spesifik
     */
    public function deleteFavorite(Request $request)
    {
        Favorite::where('user_id', Auth::user()->user_id)
                ->where('id_produk', $request->id_produk)
                ->delete();

        return response()->json(['status' => 'removed', 'message' => 'Product successfully removed']);
    }

    /**
     * Menambah produk ke keranjang belanja
     */
    public function addToCart(Request $request)
    {
        // Implementasikan logika keranjang belanja (Cart) Anda di sini
        return response()->json(['status' => 'success', 'message' => 'Product added to cart!']);
    }
}