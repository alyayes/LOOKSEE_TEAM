<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Like;
use App\Models\Comment;
use App\Models\Post; // Import Model Post (Asumsi Anda sudah membuatnya)
use App\Models\User; // Import Model User (Asumsi Anda sudah membuatnya)
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

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
        $post = Post::with('user')->findOrFail($id);
        $userId = Auth::id();

        // 1. Logika untuk menentukan status like (yang sudah kita perbaiki)
        $isLikedByUser = false;
        if ($userId) {
            $isLikedByUser = Like::where('user_id', $userId)
                                    ->where('id_post', $post->id_post)
                                    ->exists();
        }

        // 2. LOGIKA BARU: Ambil semua komentar untuk post ini, muat relasi user
        $comments = Comment::where('id_post', $post->id_post)
                        ->with('user') // Muat data pengguna yang membuat komentar
                        ->orderBy('created_at', 'asc') // Urutkan dari yang terbaru/lama
                        ->get();
        
        // 3. Kirim semua data ke view
        return view('komunitas.post_detail', [
            'post' => $post->toArray(), // Pastikan Anda mengonversi ke array jika di view menggunakan sintaks array
            'user' => $post->user ? $post->user->toArray() : [], // Asumsi user adalah relasi pada Post
            'post_items' => $post->items, // Asumsi ini sudah dimuat atau merupakan relasi
            'is_liked_by_user' => $isLikedByUser, 
            'comments' => $comments, // <<< VARIABEL INI WAJIB DIKIRIM
        ]);
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

    public function toggleLike(Request $request, $id)
    {
        // 1. Pastikan pengguna sudah login
    $userId = Auth::id();

        if (!$userId) {
            // Pengguna tidak login, kembalikan error
            return response()->json([
                'success' => false,
                'message' => 'Silakan login untuk menyukai postingan ini.'
            ], 401); 
        }

        $userId = Auth::id();
        $postId = $id;

        // Cari apakah like sudah ada
        $like = Like::where('user_id', $userId)
                    ->where('id_post', $postId)
                    ->first();

        $isLiked = false;

        if ($like) {
            // Jika like sudah ada, hapus (UNLIKE)
            $like->delete();
            $isLiked = false;
            $message = 'Postingan tidak disukai.';
        } else {
            // Jika like belum ada, buat record baru (LIKE)
            Like::create([
                'user_id' => $userId,
                'id_post' => $postId,
            ]);
            $isLiked = true;
            $message = 'Postingan disukai dan ditambahkan ke favorit Anda.';
        }

        // Hitung ulang jumlah total likes untuk post ini
        $newLikeCount = Like::where('id_post', $postId)->count();

        return response()->json([
            'success' => true,
            'is_liked' => $isLiked,
            'new_like_count' => $newLikeCount,
            'message' => $message
        ]);
    }
}