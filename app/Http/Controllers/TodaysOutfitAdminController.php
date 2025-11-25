<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TodaysOutfitAdminController extends Controller
{
    public function index()
    {
        $uploadWebDir = 'storage/uploads/admin/todays_outfit/';
        
        $posts = [
            [
                'id_post' => 101,
                'image_post' => 'to4.jpg',
                'caption' => 'Happyy today\'s, just simple fit',
                'hashtags' => '',
                'mood' => 'Neutral',
                'created_at' => '2025-06-11 16:04:29',
                'username' => 'luuccy_',
                'product_names_list' => 'Cargo Loose Jeans<br>Russ Poloshirt',
            ],
            [
                'id_post' => 102,
                'image_post' => 'to6.jpg',
                'caption' => 'Happyy today\'ss',
                'hashtags' => '',
                'mood' => 'Very Happy',
                'created_at' => '2025-06-11 14:00:49',
                'username' => 'veliya',
                'product_names_list' => 'Ribonna<br>Oro Pants',
            ],
            [
                'id_post' => 103,
                'image_post' => 'trends1.jpg',
                'caption' => 'Inspirasi OOTD Hijab dengan Outer.',
                'hashtags' => '',
                'mood' => 'Happy',
                'created_at' => '2025-06-05 09:18:38',
                'username' => 'luuccy_',
                'product_names_list' => 'Kemeja Crop<br>Jeans Highwaist',
            ],
            [
                'id_post' => 104,
                'image_post' => 'trends12.jpg',
                'caption' => 'Todays mood, effortlessly cool.',
                'hashtags' => '',
                'mood' => 'Neutral',
                'created_at' => '2025-06-05 09:09:50',
                'username' => 'griffinL',
                'product_names_list' => 'Dobujack Shirt<br>Celana Chino',
            ],
            [
                'id_post' => 105,
                'image_post' => 'trends17.jpeg',
                'caption' => 'Pilihan outfit casual yang bikin nyaman.',
                'hashtags' => '',
                'mood' => 'Neutral',
                'created_at' => '2025-06-05 09:07:15',
                'username' => 'liiviy',
                'product_names_list' => 'Basic Top',
            ],
            [
                'id_post' => 106,
                'image_post' => 'trends11.jpg',
                'caption' => 'Dressed to impress, even on a casual day',
                'hashtags' => '',
                'mood' => 'Sad',
                'created_at' => '2025-06-05 09:38:34',
                'username' => 'lucas.julian',
                'product_names_list' => 'Cargo Loose Jeans<br>Grizzly Rompi<br>Hyunbin Kaos Polos<br>Sepatu Nike',
            ],
            [
                'id_post' => 107,
                'image_post' => 'trends2.jpg',
                'caption' => 'Tetap stylish dalam suasana hati apapun.',
                'hashtags' => '',
                'mood' => 'Sad',
                'created_at' => '2025-06-05 09:23:51',
                'username' => 'carlotee_',
                'product_names_list' => 'Rok Tutu<br>Peony Blouse',
            ],
            [
                'id_post' => 108,
                'image_post' => 'trends14.jpg',
                'caption' => 'Ceria warnamu, secerah harapanmu.',
                'hashtags' => '',
                'mood' => 'Very Happy',
                'created_at' => '2025-06-05 09:18:38',
                'username' => 'syeca.my',
                'product_names_list' => 'Virly Top<br>Rok Serut Pita<br>Ladiesbag',
            ],
            [
                'id_post' => 109,
                'image_post' => 'to5.jpeg',
                'caption' => 'Your outfit speaks before, any word.',
                'hashtags' => '',
                'mood' => 'Neutral',
                'created_at' => '2025-06-05 09:06:09',
                'username' => 'lucas.julian',
                'product_names_list' => 'Jaket Boxy<br>Kaos Blacky',
            ],
            [
                'id_post' => 110,
                'image_post' => 'trends26.jpeg',
                'caption' => 'Minimal effort, maximum impact.',
                'hashtags' => '',
                'mood' => 'Very Sad',
                'created_at' => '2025-06-05 09:02:07',
                'username' => 'griffinL',
                'product_names_list' => 'Kemeja Katun Casua<br>Black Wide Jeans',
            ],
            [
                'id_post' => 111,
                'image_post' => 'trends25.jpeg',
                'caption' => 'Keep it simple, stay stylish, effortlessly.',
                'hashtags' => '',
                'mood' => 'Neutral',
                'created_at' => '2025-06-05 08:54:56',
                'username' => 'luis',
                'product_names_list' => 'Denim Overshirt<br>Chunky Patent Oxfords<br>Kacamata Retro<br>Black Wide Jeans',
            ],
            [
                'id_post' => 112,
                'image_post' => 'trends16.jpeg',
                'caption' => 'Santai tapi tetap memikat!',
                'hashtags' => '',
                'mood' => 'Sad',
                'created_at' => '2025-06-05 08:48:19',
                'username' => 'whollyy',
                'product_names_list' => 'Hooligans Sweater<br>Jeans Boyfriend',
            ],
            [
                'id_post' => 113,
                'image_post' => 'trends3.jpg',
                'caption' => 'Tampil Elegan dengan Simplicity',
                'hashtags' => '',
                'mood' => 'Neutral',
                'created_at' => '2025-06-04 16:19:13',
                'username' => 'luuccy_',
                'product_names_list' => 'Gia Jeans Highwaist<br>Sheen Pashmina Silk<br>Ladiesbag<br>Executive Sleeve Stripes',
            ],
            [
                'id_post' => 114,
                'image_post' => 'trends19.jpeg',
                'caption' => 'Gloomy day, gloomy outfit',
                'hashtags' => '',
                'mood' => 'Happy',
                'created_at' => '2025-06-04 16:16:55',
                'username' => 'whollyy',
                'product_names_list' => 'Madless Shirt<br>Celana Cargo',
            ],
        ];

        return view('todaysOutfitAdmin.toAdmin', [
            'posts' => $posts,
            'uploadWebDir' => $uploadWebDir,
        ]);
    }
}