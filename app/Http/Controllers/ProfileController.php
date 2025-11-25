<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\CommunityController; 

class ProfileController extends Controller
{
        private function getDummyUserProfile()
    {
        return [
            'user_id' => 1,
            'username' => 'looksee_user',
            'name' => 'Fashion Enthusiast',
            'email' => 'user@looksee.com',
            'profile_picture' => 'profile1.jpg',
            'bio' => 'Style is a way to say who you are without having to speak.',
            'birthday' => '1998-05-15',
            'country' => 'Indonesia',
            'phone' => '081234567890',
            'twitter' => '@looksee_style',
            'facebook' => 'facebook.com/looksee',
            'instagram' => '@looksee_id',
        ];
    }

    public function showSettings()
    {
        $user = $this->getDummyUserProfile();
        
        // Mpath gambar profil
        $profilePicture = !empty($user['profile_picture']) 
            ? asset('assets/images/profile/profile1.jpeg' . $user['profile_picture']) 
            : asset('assets/images/default-profile.png');
            

        return view('profile.settings', compact('user', 'profilePicture'));
    }

    private function getDummyData() {
        return (new CommunityController)->getDummyData();
    }

    /**
     * user_id = 1
     */
    public function index()
    {
        $data = $this->getDummyData();
        $user_id_logged_in = 1; 

        $userData = $data['users'][$user_id_logged_in] ?? null;
        if (!$userData) {
            abort(404, 'User not found.');
        }

        // Filter posts untuk hanya menampilkan post milik user ID 1
        $user_posts = collect($data['posts'])->where('user_id', $user_id_logged_in)->sortByDesc('created_at');

        return view('profile.profile', [ 
            'userData' => $userData,
            'posts' => $user_posts,
            'gallery_posts' => $user_posts,
        ]);
    }

    /**
     * Menangani upload gambar awal (dummy).
     */
    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        if ($request->file('image')) {
            // Buat nama file acak (dummy)
            $fileName = Str::random(20) . '.' . $request->file('image')->getClientOriginalExtension();
            
            // Simpan nama file di session untuk halaman create post
            session(['uploaded_image' => $fileName]);

            // Redirect ke halaman pembuatan post
            return redirect()->route('profile.post.create');
        }

        return back()->with('error', 'Image upload failed. Please try again.');
    }
    
    /**
     * Menampilkan form untuk membuat post baru.
     */
    public function showCreatePostForm()
    {
        $image_filename = session('uploaded_image');

        if (!$image_filename) {
            // Redirect jika user akses URL ini tanpa upload gambar dulu
            return redirect()->route('profile.profile')->with('error', 'Please upload an image first.');
        }
        
        // Dummy data produk untuk modal
        $all_products = [
            ['id_produk' => 'A01', 'nama_produk' => 'Beige Trench Coat', 'gambar_produk' => '1.jpeg'],
            ['id_produk' => 'A02', 'nama_produk' => 'Classic Leather Boots', 'gambar_produk' => '3.jpeg'],
            ['id_produk' => 'B01', 'nama_produk' => 'Vintage Denim Jacket', 'gambar_produk' => '4.jpeg'],
            ['id_produk' => 'C01', 'nama_produk' => 'Gia Jeans Highwaist', 'gambar_produk' => '4.jpg'],
        ];
        
        $imagePath = '\assets\images\todays outfit\to4.jpg';

        return view('komunitas.create_post', [
            'imagePath' => $imagePath,
            'all_products' => $all_products,
        ]);
    }

    /**
     * Menyimpan post baru dari form (dummy).
     */
    public function storePost(Request $request)
    {
        $validated = $request->validate([
            'caption' => 'required|string|max:255',
            'hashtags' => 'nullable|string',
            'mood' => 'required|string',
            'selected_product_ids' => 'nullable|array',
        ]);

        // Hapus session gambar setelah berhasil submit
        session()->forget('uploaded_image');

        // Redirect ke profil dengan pesan sukses
        return redirect()->route('profile.index')->with('success', 'Your style has been posted! (Dummy)');
    }
}