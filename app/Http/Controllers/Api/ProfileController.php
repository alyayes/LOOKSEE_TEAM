<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\UserAddress;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    // GET: /api/profile
    public function index(Request $request)
    {
        // AMAN: Mengambil user asli dari token
        $user = $request->user(); 

        $addresses = UserAddress::where('user_id', $user->id)->get();
        $posts = Post::where('user_id', $user->id)
                     ->orderBy('created_at', 'desc')
                     ->get();

        return response()->json([
            'success' => true,
            'message' => 'Data profil berhasil diambil',
            'data'    => [
                'user'      => $user,
                'addresses' => $addresses,
                'posts'     => $posts,
            ]
        ], 200);
    }

    // POST: /api/profile/update
    public function update(Request $request)
    {
        $user = $request->user(); // Ambil dari token

        $validator = Validator::make($request->all(), [
            'username'        => 'required|string|max:255',
            'name'            => 'required|string|max:255',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        // Logic Upload Foto Profil
        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $filename = time() . '_' . $file->getClientOriginalName();
            
            $destinationPath = public_path('assets/images/profile');
            $file->move($destinationPath, $filename);

            if ($user->profile_picture && File::exists($destinationPath . '/' . $user->profile_picture)) {
                File::delete($destinationPath . '/' . $user->profile_picture);
            }

            $user->profile_picture = $filename;
        }

        // Update data (gunakan input(...) agar aman jika field null)
        $user->username = $request->input('username', $user->username);
        $user->name     = $request->input('name', $user->name);
        $user->bio      = $request->input('bio', $user->bio);
        $user->birthday = $request->input('birthday', $user->birthday);
        $user->country  = $request->input('country', $user->country);
        $user->phone    = $request->input('phone', $user->phone);
        
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'data'    => $user
        ], 200);
    }

    // POST: /api/profile/post
    public function storePost(Request $request)
    {
        $user = $request->user(); // Ambil dari token

        $validator = Validator::make($request->all(), [
            'caption'    => 'required|string|max:255',
            'mood'       => 'required|string',
            'hashtags'   => 'nullable|string',
            'image'      => 'required|image|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $filename = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            
            $path = public_path('assets/images/todays outfit');
            $file->move($path, $filename);
        }

        $post = Post::create([
            'user_id'    => $user->id, // Otomatis pakai ID pemilik token
            'caption'    => $request->caption,
            'hashtags'   => $request->hashtags,
            'mood'       => $request->mood,
            'image_post' => $filename,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Your style has been posted!',
            'data'    => $post
        ], 201);
    }

    // POST: /api/profile/post/{id}
    public function updatePost(Request $request, $id)
    {
        $user = $request->user(); // Ambil dari token
        $post = Post::where('id_post', $id)->first();

        if (!$post) return response()->json(['message' => 'Post tidak ditemukan'], 404);
        
        // SECURITY CHECK: Pastikan yang edit adalah pemilik asli
        if ($post->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized - Ini bukan postingan Anda'], 403);
        }

        $validator = Validator::make($request->all(), [
            'caption'  => 'required|string|max:255',
            'mood'     => 'required|string',
            'hashtags' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $post->update([
            'caption'  => $request->caption,
            'mood'     => $request->mood,
            'hashtags' => $request->hashtags,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Postingan berhasil diperbarui',
            'data'    => $post
        ], 200);
    }

    // DELETE: /api/profile/post/{id}
    public function destroyPost(Request $request, $id)
    {
        $user = $request->user(); // Ambil dari token
        $post = Post::where('id_post', $id)->first();

        if (!$post) return response()->json(['message' => 'Post tidak ditemukan'], 404);
        
        // SECURITY CHECK
        if ($post->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized - Dilarang hapus postingan orang lain'], 403);
        }

        $imagePath = public_path('assets/images/todays outfit/' . $post->image_post);
        if (File::exists($imagePath)) {
            File::delete($imagePath);
        }

        $post->delete();

        return response()->json([
            'success' => true,
            'message' => 'Postingan berhasil dihapus'
        ], 200);
    }
}