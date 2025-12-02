<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Like;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class CommunityController extends Controller
{
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

    // Detail post
    public function showPostDetail($id)
    {
        $post = Post::with(['user', 'items.produk', 'comments.user', 'likes'])->findOrFail($id);
        $userId = Auth::id();

        $isLikedByUser = $userId ? $post->likes()->where('user_id', $userId)->exists() : false;

        return view('komunitas.post_detail', [
            'post' => $post,
            'user' => $post->user,
            'post_items' => $post->items,
            'is_liked_by_user' => $isLikedByUser,
            'comments' => $post->comments
        ]);
    }

    // Toggle like/unlike
    public function toggleLike(Request $request, $id)
    {
        $userId = Auth::id();
        if (!$userId) {
            return response()->json(['success' => false, 'message' => 'Login required'], 401);
        }

        $like = Like::where('user_id', $userId)->where('id_post', $id)->first();
        if ($like) {
            $like->delete();
            $isLiked = false;
        } else {
            Like::create(['user_id' => $userId, 'id_post' => $id]);
            $isLiked = true;
        }

        // Update like_count di post
        $likeCount = Like::where('id_post', $id)->count();
        $post = Post::findOrFail($id);
        $post->like_count = $likeCount;
        $post->save();

        return response()->json([
            'success' => true,
            'is_liked' => $isLiked,
            'new_like_count' => $likeCount
        ]);
    }

    // Tambah komentar
    public function addComment(Request $request, $id)
    {
        $userId = Auth::id();
        if (!$userId) return redirect()->back()->with('error', 'Login required');

        $request->validate([
            'comment_text' => 'required|string|max:500'
        ]);

        Comment::create([
            'user_id' => $userId,
            'id_post' => $id,
            'comment_text' => $request->comment_text
        ]);

        // Update comment_count di post
        $commentCount = Comment::where('id_post', $id)->count();
        $post = Post::findOrFail($id);
        $post->comment_count = $commentCount;
        $post->save();

        return redirect()->back()->with('success', 'Comment added successfully.');
    }

    // Share post
    public function sharePost(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        $post->share_count = $post->share_count + 1;
        $post->save();

        return response()->json([
            'success' => true,
            'share_count' => $post->share_count
        ]);
    }
}
