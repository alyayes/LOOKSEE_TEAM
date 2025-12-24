<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class TodaysOutfitAdminController extends Controller
{
    public function index()
    {
        $uploadWebDir = 'storage/uploads/admin/todays_outfit/';

        $postsData = Post::with(['user', 'items'])
                        ->orderBy('created_at', 'desc')
                        ->get();

        $posts = $postsData->map(function ($post) {
            $productList = $post->items->pluck('nama_produk')->implode('<br>'); 

            return [
                'id_post' => $post->id_post,
                'image_post' => $post->image_post,
                'caption' => $post->caption,
                'hashtags' => $post->hashtags,
                'mood' => $post->mood,
                'created_at' => $post->created_at,
                'username' => $post->user ? $post->user->username : 'Unknown', 
                'product_names_list' => $productList, 
            ];
        });

        return view('admin.todaysOutfitAdmin.toAdmin', [
            'posts' => $posts,
            'uploadWebDir' => $uploadWebDir,
        ]);
    }
}