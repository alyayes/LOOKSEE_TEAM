<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FavoriteController extends Controller
{
   
    public function index()
    {
        $liked_posts = [
            ['id_post' => 101, 'image_post' => 'trends12.jpg', 'caption' => 'Loving this cozy autumn look!', 'username' => 'afa_style', 'mood' => 'Happy', 'total_likes' => 152, 'profile_picture' => 'profile1.jpg'],
            ['id_post' => 103, 'image_post' => 'trends14.jpg', 'caption' => 'Sunday brunch fit.', 'username' => 'afa_style', 'mood' => 'Very Happy', 'total_likes' => 305, 'profile_picture' => 'profile1.jpg'],
        ];

        $favorite_products = [
            ['id_fav' => 201, 'id_produk' => 'B01', 'nama_produk' => 'Vintage Denim Jacket', 'harga' => 550000, 'gambar_produk' => 'tutu.jpg'],
            ['id_fav' => 202, 'id_produk' => 'A01', 'nama_produk' => 'Beige Trench Coat', 'harga' => 750000, 'gambar_produk' => 'slimfit.jpg'],
        ];

        return view('favorite.favorite', compact('liked_posts', 'favorite_products'));
    }

    public function deleteFavorite(Request $request)
    {
        $validated = $request->validate(['id_favorite' => 'required|numeric']);

        return response()->json([
            'success' => true,
            'message' => 'Product successfully removed from favorites! (Dummy)'
        ]);
    }


    public function addToCart(Request $request)
    {
        $validated = $request->validate(['id_produk' => 'required']);

        return response()->json([
            'status' => 'success',
            'message' => 'Product added to cart! (Dummy)'
        ]);
    }
}