<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Produk;
use App\Models\UserAddress;
use Illuminate\Http\Request;

class ProfileController extends Controller
{

    public function index()
    {
        $user = User::first(); 
        if (!$user) abort(404, 'User tidak ditemukan');

        $addresses = UserAddress::where('user_id', $user->id)->get();
        $products = Produk::all();

        $posts = [];
        $gallery_posts = [];

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
        $user = User::first();
        if (!$user) abort(404, 'User tidak ditemukan');

        return view('profile.settings', [
            'user' => $user
        ]);
    }

        public function updateSettings(Request $request)
    {
        $user = User::first();
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

        // Handle foto profile
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
        $user = auth()->user(); 
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
        // Mengambil nama file dari parameter URL (query string)
        $filename = $request->query('temp_image_filename');
        
        if (!$filename) {
            // Jika tidak ada gambar, redirect kembali ke profil
            return redirect()->route('profile.index')->with('error', 'Anda harus mengunggah gambar terlebih dahulu.');
        }

        // Mendapatkan semua produk untuk modal tagging
        $all_products = Produk::all(); 

        // Tentukan path gambar yang akan ditampilkan di blade
        $imagePath = asset('assets/images/todays outfit/' . $filename);
        
        return view('profile.create_post', [
            'imagePath' => $imagePath,
            'imageFilename' => $filename, // Nama file untuk disimpan di DB saat POST
            'all_products' => $all_products,
        ]);
    }
}
