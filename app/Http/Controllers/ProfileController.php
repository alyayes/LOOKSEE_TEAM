<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    /**
     * Menampilkan halaman settings profil.
     */
    public function showSettings()
    {
        $user = Auth::user();

        $profilePicture = $user->profile_picture
            ? asset('storage/profile/' . $user->profile_picture)
            : asset('assets/images/default-profile.png');

        return view('profile.settings', compact('user', 'profilePicture'));
    }

    /**
     * Halaman profil utama (post user).
     */
    public function index()
    {
        $user = Auth::user();

        $posts = Post::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->get();

        return view('profile.profile', [
            'userData' => $user,
            'posts' => $posts,
            'gallery_posts' => $posts,
        ]);
    }

    /**
     * Upload gambar sebelum buat post.
     */
    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:4096',
        ]);

        $file = $request->file('image');
        $filename = Str::random(20) . '.' . $file->getClientOriginalExtension();

        $file->storeAs('public/temp_posts', $filename);

        session(['uploaded_image' => $filename]);

        return redirect()->route('profile.post.create');
    }

    /**
     * Form buat post baru.
     */
    public function showCreatePostForm()
    {
        $image_filename = session('uploaded_image');
        if (!$image_filename) {
            return redirect()->route('profile.index')->with('error', 'Upload an image first.');
        }

        // Ambil produk dari DB
        $all_products = Produk::all();

        $imagePath = asset('storage/temp_posts/' . $image_filename);

        return view('komunitas.create_post', [
            'imagePath' => $imagePath,
            'all_products' => $all_products,
        ]);
    }

    /**
     * Menyimpan post ke database.
     */
    public function storePost(Request $request)
    {
        $validated = $request->validate([
            'caption' => 'required|string|max:255',
            'hashtags' => 'nullable|string',
            'mood' => 'required|string',
            'selected_product_ids' => 'nullable|array',
        ]);

        $user = Auth::user();
        $tempFile = session('uploaded_image');

        if (!$tempFile) {
            return redirect()->route('profile.index')->with('error', 'No image uploaded.');
        }

        // Pindahkan file dari /temp_posts ke /posts
        Storage::move("public/temp_posts/" . $tempFile, "public/posts/" . $tempFile);

        // Simpan post ke DB
        Post::create([
            'user_id' => $user->id,
            'caption' => $validated['caption'],
            'hashtags' => $validated['hashtags'],
            'mood' => $validated['mood'],
            'image_filename' => $tempFile,
        ]);

        session()->forget('uploaded_image');

        return redirect()->route('profile.index')
            ->with('success', 'Your style has been posted!');
    }
}
