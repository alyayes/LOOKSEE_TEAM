<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Produk;
use App\Models\Post; 
use App\Models\Like;
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

    // --- PENAMBAHAN LOGIKA UNTUK $liked_posts ---
        
        // Dapatkan ID post yang di-like (favorit) oleh user saat ini
        $liked_post_ids = Like::where('user_id', $user->id)
                                ->pluck('id_post');

        // Ambil detail Post, tambahkan hitungan likes (total_likes), dan muat relasi user
        $liked_posts = Post::whereIn('id_post', $liked_post_ids)
                            // Muat relasi user agar kita bisa mendapatkan username/profile_picture
                            ->with('user') 
                            // Tambahkan hitungan likes yang dibutuhkan di view ({{ $post['total_likes'] }})
                            ->withCount('likes as total_likes') 
                            ->get()
                            ->map(function ($post) {
                                // Gabungkan data user ke dalam array post untuk akses mudah di view
                                return array_merge($post->toArray(), [
                                    'username' => $post->user->username ?? 'Unknown User',
                                    'profile_picture' => $post->user->profile_picture ?? null,
                                ]);
                            })->toArray(); 
        
        // --- PENERUSAN SEMUA VARIABEL ---
        // $favorite_products dan $user sudah ada, TAMBAHKAN $liked_posts
        return view('favorite.favorite', compact('favorite_products', 'user', 'liked_posts'));
    }
}
