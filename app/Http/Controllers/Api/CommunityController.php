<?php

namespace App\Http\Controllers\Api; // 1. Namespace berubah ke folder Api

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Like;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class CommunityController extends Controller
{
    public function trends()
    {
        // Mengambil data posts dengan relasi user
        $posts = Post::with('user')->orderByDesc('like_count')->get();

        // 2. Return JSON
        return response()->json([
            'success' => true,
            'message' => 'List trends loaded',
            'data'    => $posts
        ], 200);
    }

    public function todaysOutfit()
    {
        $posts = Post::with('user')->orderByDesc('created_at')->get();

        return response()->json([
            'success' => true,
            'message' => 'List todays outfit loaded',
            'data'    => $posts
        ], 200);
    }

    public function showPostDetail($id)
    {
        // Cari post, jika tidak ketemu return 404
        $post = Post::with(['user', 'items.produk', 'comments.user', 'likes'])->find($id);

        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Post not found',
            ], 404);
        }

        // Cek apakah user sedang login via Token untuk status 'is_liked'
        // Menggunakan guard 'sanctum' untuk memastikan membaca token API
        $userId = Auth::guard('sanctum')->id(); 

        $isLikedByUser = $userId ? $post->likes()->where('user_id', $userId)->exists() : false;

        return response()->json([
            'success' => true,
            'message' => 'Post detail loaded',
            'data'    => [
                'post' => $post,
                'is_liked_by_user' => $isLikedByUser,
                // Data relasi (comments, items) sudah ada di dalam object $post karena 'with' di atas
                // Tapi jika ingin dipisah strukturnya bisa seperti ini:
                // 'comments' => $post->comments 
            ]
        ], 200);
    }

    public function toggleLike(Request $request, $id)
    {
        // Pastikan request memiliki token yang valid
        $user = $request->user(); 

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $userId = $user->id;

        $like = Like::where('user_id', $userId)->where('id_post', $id)->first();
        
        if ($like) {
            $like->delete();
            $isLiked = false;
        } else {
            Like::create(['user_id' => $userId, 'id_post' => $id]);
            $isLiked = true;
        }

        // Hitung ulang like
        $likeCount = Like::where('id_post', $id)->count();
        $post = Post::findOrFail($id);
        $post->like_count = $likeCount;
        $post->save();

        return response()->json([
            'success' => true,
            'message' => $isLiked ? 'Liked successfully' : 'Unliked successfully',
            'data' => [
                'is_liked' => $isLiked,
                'new_like_count' => $likeCount
            ]
        ], 200);
    }

    public function addComment(Request $request, $id)
    {
        // Cek user login via token
        $user = $request->user();
        if (!$user) {
             return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        // Validasi input
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'comment_text' => 'required|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error',
                'errors'  => $validator->errors()
            ], 422);
        }

        // Simpan komentar
        $comment = Comment::create([
            'user_id' => $user->id,
            'id_post' => $id,
            'comment_text' => $request->comment_text
        ]);

        // Update jumlah komentar di post
        $commentCount = Comment::where('id_post', $id)->count();
        $post = Post::findOrFail($id);
        $post->comment_count = $commentCount;
        $post->save();
        
        // Load data user si pengomentar agar bisa langsung ditampilkan di Mobile App tanpa refresh
        $comment->load('user');

        return response()->json([
            'success' => true,
            'message' => 'Comment added successfully',
            'data' => [
                'comment' => $comment, // Mengembalikan data komen baru
                'new_comment_count' => $commentCount
            ]
        ], 201);
    }

    public function sharePost(Request $request, $id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json(['success' => false, 'message' => 'Post not found'], 404);
        }

        $post->share_count = $post->share_count + 1;
        $post->save();

        return response()->json([
            'success' => true,
            'message' => 'Share count updated',
            'data' => [
                'share_count' => $post->share_count
            ]
        ], 200);
    }
}