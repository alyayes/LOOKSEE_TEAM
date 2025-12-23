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
        $posts = Post::with('user')->orderByDesc('like_count')->get();

        return view('komunitas.trends', compact('posts'));
    }

    public function todaysOutfit()
    {
        $posts = Post::with('user')->orderByDesc('created_at')->get();

        return view('komunitas.todays_outfit', compact('posts'));
    }

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

        $commentCount = Comment::where('id_post', $id)->count();
        $post = Post::findOrFail($id);
        $post->comment_count = $commentCount;
        $post->save();

        return redirect()->back()->with('success', 'Comment added successfully.');
    }

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