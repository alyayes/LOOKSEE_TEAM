<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post; // Import Model Post (Asumsi Anda sudah membuatnya)
use App\Models\User; // Import Model User (Asumsi Anda sudah membuatnya)
use Carbon\Carbon;

class CommunityController extends Controller
{
    /**
     * Menghapus fungsi getDummyData() karena data diambil dari database.
     */

    /**
     * Menampilkan daftar post yang sedang tren (berdasarkan like_count tertinggi).
     * Menggunakan Eloquent untuk mengambil data dari database.
     */
    public function trends()
    {
        // Ambil semua post, urutkan berdasarkan like_count secara descending (tertinggi)
        // Gunakan with('user') untuk memuat data user yang berelasi agar tidak terjadi N+1 problem.
        $posts = Post::with('user')->orderByDesc('like_count')->get();

        // Data users tidak perlu diambil secara terpisah jika relasi sudah didefinisikan di Model Post.
        // Jika tetap memerlukan koleksi user terpisah, bisa diabaikan atau diambil jika diperlukan di view.

        return view('komunitas.trends', compact('posts'));
    }

    /**
     * Menampilkan daftar post terbaru (Outfit Hari Ini).
     * Menggunakan Eloquent untuk mengambil data dari database.
     */
    public function todaysOutfit()
    {
        // Ambil semua post, urutkan berdasarkan created_at secara descending (terbaru)
        $posts = Post::with('user')->orderByDesc('created_at')->get();

        return view('komunitas.todaysOutfit', compact('posts'));
    }

    /**
     * Menampilkan detail dari sebuah post.
     * Menggunakan Eloquent untuk mengambil data dari database.
     */
    public function showPostDetail($id)
    {
        // Gunakan findOrFail untuk mencari post berdasarkan id_post (primary key)
        // dan secara otomatis melempar 404 jika tidak ditemukan.
        // Eager load relasi 'user', 'comments', dan 'items' (jika ada relasi untuk post_items).
        $post = Post::with(['user', 'comments'])->findOrFail($id);
        
        // Data relasi (user dan comments) sudah tersedia di objek $post
        $user = $post->user;
        $comments = $post->comments;
        
        // Asumsi relasi 'items' ada untuk post_items, jika tidak, Anda perlu membuat Model dan Relasinya
        // Jika post_items (produk yang ditandai) ada dalam tabel terpisah yang berelasi dengan 'posts',
        // maka Anda dapat mengambilnya seperti:
        $post_items = $post->items ?? collect(); // Asumsi $post->items adalah relasi
        
        // Jika 'comments' sudah di-eager load, Anda tidak perlu loop lagi.
        // Tapi jika Anda ingin memuat data user dalam setiap komentar, 
        // pastikan relasi 'user' didefinisikan di Model Comment dan di-eager load.
        // Contoh: $post = Post::with(['user', 'comments.user'])->findOrFail($id);

        return view('komunitas.post_detail', compact('post', 'user', 'post_items', 'comments'));
    }

    // Fungsi likePost, addComment, dan sharePost tetap dipertahankan sebagai fungsi dummy
    // Namun dalam aplikasi nyata, ini harus dimodifikasi untuk berinteraksi dengan database.
    public function likePost(Request $request, $id) { 
        // Logika untuk menambah like ke database harus ada di sini (misalnya Post::find($id)->increment('like_count'))
        return response()->json(['success' => true, 'liked' => true, 'like_count' => Post::findOrFail($id)->like_count + 1]); 
    }
    public function addComment(Request $request, $id) { 
        // Logika untuk menyimpan komentar ke database harus ada di sini
        return redirect()->back()->with('success', 'Comment posted! (Still Dummy)'); 
    }
    public function sharePost(Request $request, $id) { 
        // Logika untuk menambah share count ke database harus ada di sini
        return response()->json(['success' => true, 'share_count' => Post::findOrFail($id)->share_count + 1]); 
    }
}