<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Produk;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;


class ProfileController extends Controller
{

    public function index()
    {
        $user = Auth::user(); 
        if (!$user) abort(404, 'User tidak ditemukan');

        $addresses = UserAddress::where('user_id', $user->id)->get();
        $products = Produk::all();

        $posts = Post::where('user_id', $user->id)
                     ->orderBy('created_at', 'desc') 
                     ->get();
        $gallery_posts = $posts;

        return view('profile.profile', [
            'userData' => $user,
            'addresses' => $addresses,
            'products' => $products,
            'posts' => $posts,
            'gallery_posts' => $gallery_posts,
        ]);
    }

    public function showSettings()
    {
        $user = Auth::user();
        if (!$user) abort(404, 'User tidak ditemukan');

        return view('profile.settings', [
            'user' => $user
        ]);
    }

    public function updateSettings(Request $request)
    {
        $user = Auth::user();
        if (!$user) abort(404, 'User tidak ditemukan');

        $request->validate([
            'username' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:800',
            'bio' => 'nullable|string|max:500',
            'birthday' => 'nullable|date',
            'country' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
            'twitter' => 'nullable|string|max:100',
            'facebook' => 'nullable|string|max:100',
            'instagram' => 'nullable|string|max:100',
        ]);

        // Foto profil
        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('assets/images/profile');
            $file->move($destinationPath, $filename);

            // Hapus foto lama
            if ($user->profile_picture && file_exists($destinationPath . '/' . $user->profile_picture)) {
                unlink($destinationPath . '/' . $user->profile_picture);
            }

            $user->profile_picture = $filename;
        }

        // Update data lain
        $user->username = $request->username;
        $user->name = $request->name;
        $user->bio = $request->bio;
        $user->birthday = $request->birthday;
        $user->country = $request->country;
        $user->phone = $request->phone;
        $user->twitter = $request->twitter;
        $user->facebook = $request->facebook;
        $user->instagram = $request->instagram;

        $user->save();

        return redirect()->route('profile.settings')->with('success', 'Profile updated successfully.');
    }

    public function uploadImage(Request $request)
    {
        $user = Auth::user(); 
        if (!$user) {
            return redirect()->route('login')->with('error', 'Authentication required.');
        }

        $request->validate([
            'image' => 'required|image|max:2048'
        ]);

        $file = $request->file('image');
        $filename = time().'_'.$file->getClientOriginalName();

        $publicPath = public_path('assets/images/todays outfit');
        $file->move($publicPath, $filename);

        return redirect()->route('profile.post.create', ['temp_image_filename' => $filename]);
    }

    public function showCreatePostForm(Request $request)
    {
        $filename = $request->query('temp_image_filename');
        
        if (!$filename) {
            return redirect()->route('profile.index')->with('error', 'Anda harus mengunggah gambar terlebih dahulu.');
        }

        $all_products = Produk::all(); 

        $imagePath = asset('assets/images/todays outfit/' . $filename);
        
        return view('komunitas.create_post', [
            'imagePath' => $imagePath,
            'imageFilename' => $filename,
            'all_products' => $all_products,
        ]);
    }

    public function storePost(Request $request)
    {
        $user = Auth::user();
        
        if (!$user) {
             return redirect()->route('login')->with('error', 'Authentication required.'); 
        }

        $validated = $request->validate([
            'caption' => 'required|string|max:255',
            'hashtags' => 'nullable|string',
            'mood' => 'required|string',
        ]);

        $finalFilename = $request->input('imageFilename'); 

        if (!$finalFilename) {
            return redirect()->route('profile.post.create')->with('error', 'Tidak ada gambar untuk diposting.');
        }

        
        Post::create([
            'user_id' => $user->id,
            'caption' => $validated['caption'],
            'hashtags' => $validated['hashtags'],
            'mood' => $validated['mood'],
            'image_post' => $finalFilename,
        ]);

        return redirect()->route('profile.index')
            ->with('success', 'Your style has been posted!');
    }

    public function showEditPostForm($id)
    {
        $user = Auth::user();
        $post = Post::where('id_post', $id)->first(); 

        if (!$post || $post->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki izin untuk mengedit postingan ini.');
        }

        return view('komunitas.edit_post', [
            'post' => $post,
        ]);
    }

    public function updatePost(Request $request, $id)
    {
        $user = Auth::user();
        $post = Post::where('id_post', $id)->first();

        if (!$post || $post->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki izin.');
        }

        $validated = $request->validate([
            'caption' => 'required|string|max:255',
            'hashtags' => 'nullable|string',
            'mood' => 'required|string',
        ]);

        $post->caption = $validated['caption'];
        $post->hashtags = $validated['hashtags'];
        $post->mood = $validated['mood'];
        $post->save();

        return redirect()->route('profile.index')
            ->with('success', 'Postingan berhasil diperbarui.');
    }

    public function destroyPost($id)
    {
        $user = Auth::user();
        $post = Post::where('id_post', $id)->first();

        if (!$post || $post->user_id !== $user->id) {
            return redirect()->route('profile.index')->with('error', 'Anda tidak memiliki izin untuk menghapus postingan ini.');
        }

        $imagePath = public_path('assets/images/todays outfit/' . $post->image_post);

        if (File::exists($imagePath)) {
            File::delete($imagePath);
        }

        $post->delete();

        return redirect()->route('profile.index')
            ->with('success', 'Postingan berhasil dihapus.');
    }
}
