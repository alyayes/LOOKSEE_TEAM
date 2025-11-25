<?php

namespace App\Http\Controllers;

    use Illuminate\Http\Request;
    use Illuminate\Support\Str;
    use Carbon\Carbon;

    class CommunityController extends Controller
    {
        /**
         * Sumber data dummy untuk semua post dan user.
         * Dibuat public agar bisa diakses oleh Controller lain.
         */
        public function getDummyData()
        {
            return [
                'users' => [
                    1 => ['user_id' => 1, 'username' => 'luuccy_', 'name' => 'Lucy Maudy', 'profile_picture' => '23d.jpeg', 'bio' => 'Fashion enthusiast & style blogger. Sharing my daily looks!', 'birthday' => '1998-05-15', 'country' => 'Indonesia', 'instagram' => 'afa_style', 'email' => 'afa@looksee.com', 'twitter' => '@afastyle', 'facebook' => 'Afa Nur Fauziah'],
                    2 => ['user_id' => 2, 'username' => 'dior_majory', 'name' => 'Dior Majorie', 'profile_picture' => 'profile2.jpeg', 'bio' => 'Casual fits and city vibes.', 'birthday' => '2000-01-20', 'country' => 'Indonesia', 'instagram' => 'dior.majory', 'email' => 'dior@looksee.com', 'twitter' => '', 'facebook' => ''],
                ],
                'posts' => [
                    101 => ['id_post' => 101, 'user_id' => 1, 'image_post' => 'trends1.jpg', 'caption' => 'Loving this cozy autumn look!', 'mood' => 'Happy', 'hashtags' => '#autumn #cozy #ootd', 'like_count' => 152, 'comment_count' => 12, 'share_count' => 5, 'created_at' => now()->subDays(1)],
                    102 => ['id_post' => 102, 'user_id' => 2, 'image_post' => 'trends2.jpg', 'caption' => 'City lights and denim nights.', 'mood' => 'Neutral', 'hashtags' => '#denim #citylife', 'like_count' => 210, 'comment_count' => 25, 'share_count' => 8, 'created_at' => now()->subDays(2)],
                    103 => ['id_post' => 103, 'user_id' => 1, 'image_post' => 'trends3.jpg', 'caption' => 'Sunday brunch fit.', 'mood' => 'Very Happy', 'hashtags' => '#brunch #sundayfunday', 'like_count' => 305, 'comment_count' => 40, 'share_count' => 15, 'created_at' => now()->subDays(3)],
                    104 => ['id_post' => 104, 'user_id' => 2, 'image_post' => 'trends4.jpg', 'caption' => 'Feeling blue, but in a good way!', 'mood' => 'Sad', 'hashtags' => '#monochrome #blue', 'like_count' => 98, 'comment_count' => 7, 'share_count' => 2, 'created_at' => now()->subDays(4)],
                ],
                'post_items' => [ // Produk yang ditandai di post
                    101 => [
                        ['id_produk' => 'A01', 'nama_produk' => 'Beige Trench Coat', 'gambar_produk' => 't1.jpg', 'harga' => 750000],
                        ['id_produk' => 'A02', 'nama_produk' => 'Classic Leather Boots', 'gambar_produk' => 't3.jpg', 'harga' => 1200000],
                    ],
                    102 => [
                        ['id_produk' => 'B01', 'nama_produk' => 'Vintage Denim Jacket', 'gambar_produk' => 't4.jpg', 'harga' => 550000],
                    ],
                    103 => [], 
                    104 => [],
                ],
                'comments' => [
                    101 => [
                        ['user_id' => 2, 'comment_text' => 'Love the coat! Where is it from?', 'created_at' => now()->subDay()],
                    ],
                    102 => [
                        ['user_id' => 1, 'comment_text' => 'Cool vibe!', 'created_at' => now()->subDays(2)->addHour()],
                    ],
                ]
            ];
        }

        public function trends()
        {
            $data = $this->getDummyData();
            $posts = collect($data['posts'])->sortByDesc('like_count');
            $users = collect($data['users'])->keyBy('user_id');
            return view('komunitas.trends', compact('posts', 'users'));
        }

        public function todaysOutfit()
        {
            $data = $this->getDummyData();
            $posts = collect($data['posts'])->sortByDesc('created_at');
            $users = collect($data['users'])->keyBy('user_id');
            return view('komunitas.todaysOutfit', compact('posts', 'users'));
        }

        public function showPostDetail($id)
        {
            $data = $this->getDummyData();
            $post = $data['posts'][(int)$id] ?? null;
            if (!$post) abort(404, 'Post not found.');

            $user = $data['users'][$post['user_id']] ?? null;
            $post_items = $data['post_items'][(int)$id] ?? [];
            $comments_data = $data['comments'][(int)$id] ?? [];
            
            $comments = [];
            foreach($comments_data as $comment){
                $comment['user'] = $data['users'][$comment['user_id']] ?? ['username' => 'Unknown', 'profile_picture' => null];
                $comments[] = $comment;
            }

            return view('komunitas.post_detail', compact('post', 'user', 'post_items', 'comments'));
        }

        public function likePost(Request $request, $id) { return response()->json(['success' => true, 'liked' => true, 'like_count' => rand(100, 500)]); }
        public function addComment(Request $request, $id) { return redirect()->back()->with('success', 'Comment posted! (Dummy)'); }
        public function sharePost(Request $request, $id) { return response()->json(['success' => true, 'share_count' => rand(10, 50)]); }
    }
    

