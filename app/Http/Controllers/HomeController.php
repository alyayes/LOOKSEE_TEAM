<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    private $limit = 5; 
    private $moodLimit = 10;
    
    // --- Data Dummy Produk ---
    private function getProductsDummyData()
    {
        return [
            // Wanita (Woman) 
            ['id_produk' => 20, 'nama_produk' => 'Jeans Slim Fit', 'kategori' => 'Woman', 'harga' => 400000, 'gambar_produk' => 'woman4.jpeg', 'mood' => 'sad'],
            ['id_produk' => 19, 'nama_produk' => 'Basic T-Shirt', 'kategori' => 'Woman', 'harga' => 350000, 'gambar_produk' => 'woman1.jpg', 'mood' => 'netral'],
            ['id_produk' => 18, 'nama_produk' => 'Executive Sleeve Stripes', 'kategori' => 'Woman', 'harga' => 250000, 'gambar_produk' => 'woman2.jpeg', 'mood' => 'happy'],
            ['id_produk' => 17, 'nama_produk' => 'Lozy Square Hijab', 'kategori' => 'Woman', 'harga' => 500000, 'gambar_produk' => 'woman3.jpg', 'mood' => 'very happy'],
            ['id_produk' => 16, 'nama_produk' => 'Rok Denim Vintage', 'kategori' => 'Woman', 'harga' => 280000, 'gambar_produk' => 'woman5.jpg', 'mood' => 'very sad'], 
            ['id_produk' => 15, 'nama_produk' => 'Kemeja Oversized', 'kategori' => 'Woman', 'harga' => 320000, 'gambar_produk' => 'woman6.jpg', 'mood' => 'netral, very happy'], 
            ['id_produk' => 14, 'nama_produk' => 'Cardingan Pink', 'kategori' => 'Woman', 'harga' => 400000, 'gambar_produk' => 'woman7.jpg', 'mood' => 'happy'],
            ['id_produk' => 13, 'nama_produk' => 'Blouse Kantor Black', 'kategori' => 'Woman', 'harga' => 350000, 'gambar_produk' => 'woman8.jpeg', 'mood' => 'netral'],
            ['id_produk' => 12, 'nama_produk' => 'Sheen Silk', 'kategori' => 'Woman', 'harga' => 250000, 'gambar_produk' => 'woman9.png', 'mood' => 'happy'],
            ['id_produk' => 11, 'nama_produk' => 'Outer Wear Keren', 'kategori' => 'Woman', 'harga' => 500000, 'gambar_produk' => 'woman10.jpg', 'mood' => 'very happy'],
            
            // Pria (Man)
            ['id_produk' => 10, 'nama_produk' => 'Black Rompi', 'kategori' => 'Man', 'harga' => 250000, 'gambar_produk' => 'man1.png', 'mood' => 'happy'],
            ['id_produk' => 9, 'nama_produk' => 'Black Wide Jeans', 'kategori' => 'Man', 'harga' => 500000, 'gambar_produk' => 'man2.jpg', 'mood' => 'very happy'],
            ['id_produk' => 8, 'nama_produk' => 'Kemeja Oversized', 'kategori' => 'Man', 'harga' => 280000, 'gambar_produk' => 'man3.jpeg', 'mood' => 'netral'], 
            ['id_produk' => 7, 'nama_produk' => 'Sepatu Kulit Formal', 'kategori' => 'Man', 'harga' => 320000, 'gambar_produk' => 'man4.jpeg', 'mood' => 'very sad'], 
            ['id_produk' => 6, 'nama_produk' => 'Dritto Jacket', 'kategori' => 'Man', 'harga' => 450000, 'gambar_produk' => 'man5.jpg', 'mood' => 'very happy'],
            ['id_produk' => 5, 'nama_produk' => 'Kacamata Retro', 'kategori' => 'Man', 'harga' => 10000, 'gambar_produk' => 'man6.jpeg', 'mood' => 'happy'],
            ['id_produk' => 4, 'nama_produk' => 'Blue Shirt', 'kategori' => 'Man', 'harga' => 380000, 'gambar_produk' => 'man7.jpg', 'mood' => 'sad'],
            ['id_produk' => 3, 'nama_produk' => 'Stripe Green', 'kategori' => 'Man', 'harga' => 600000, 'gambar_produk' => 'man8.jpg', 'mood' => 'netral'],
            ['id_produk' => 2, 'nama_produk' => 'Methapora Shirt', 'kategori' => 'Man', 'harga' => 700000, 'gambar_produk' => 'man9.png', 'mood' => 'netral'], 
            ['id_produk' => 1, 'nama_produk' => 'Nike Running', 'kategori' => 'Man', 'harga' => 150000, 'gambar_produk' => 'man10.png', 'mood' => 'sad'], 
        ];
    }
    
    // Method Home Page (URL: /home)
    public function index(Request $request)
    {
        $allProducts = collect($this->getProductsDummyData())->sortByDesc('id_produk')->values();
        
        // --- Produk Wanita ---
        $productsWoman = $allProducts->filter(function($p) {
            return in_array($p['kategori'], ['Wanita', 'Woman']);
        })->values()->all();
        
        $pageWoman = $request->query('page_woman', 1);
        $offsetWoman = ($pageWoman - 1) * $this->limit;
        
        $productsWomanPage = array_slice($productsWoman, $offsetWoman, $this->limit);
        $totalPagesWoman = ceil(count($productsWoman) / $this->limit);

        // --- Produk Pria ---
        $productsMan = $allProducts->filter(function($p) {
            return in_array($p['kategori'], ['Pria', 'Man']);
        })->values()->all();

        $pageMan = $request->query('page_man', 1);
        $offsetMan = ($pageMan - 1) * $this->limit;
        
        $productsManPage = array_slice($productsMan, $offsetMan, $this->limit);
        $totalPagesMan = ceil(count($productsMan) / $this->limit);

        return view('home.index', [
            'productsWoman' => $productsWomanPage,
            'productsMan' => $productsManPage,
            'pageWoman' => $pageWoman,
            'totalPagesWoman' => $totalPagesWoman,
            'pageMan' => $pageMan,
            'totalPagesMan' => $totalPagesMan,
        ]);
    }
    
    // Method Selected Mood (URL: /mood)
    public function showMoodProducts(Request $request)
    {
        $allProducts = collect($this->getProductsDummyData())->sortByDesc('id_produk')->values();

        // --- 1. Ambil Input ---
        $mood = strtolower($request->query('mood', 'netral'));
        $gender = strtolower($request->query('gender', ''));
        $currentPage = $request->query('page', 1);

        // --- 2. Filter Berdasarkan Mood dan Gender ---
        $filteredProducts = $allProducts->filter(function($p) use ($mood, $gender) {
            // Logic hanya mengecek apakah string mood produk mengandung mood yang dipilih
            $isMoodMatch = str_contains(strtolower($p['mood']), $mood);

            // ... (Gender filtering logic remains the same) ...
            $kategori = strtolower($p['kategori']);
            $isGenderMatch = (!$gender) || // Jika gender kosong/All, selalu true
                             ($gender === 'men' && in_array($kategori, ['pria', 'man'])) ||
                             ($gender === 'women' && in_array($kategori, ['wanita', 'woman']));

            return $isMoodMatch && $isGenderMatch;
        })->values()->all();

        // --- 3. Logika Pagination ---
        $totalProducts = count($filteredProducts);
        $totalPages = ceil($totalProducts / $this->moodLimit);
        $offset = ($currentPage - 1) * $this->moodLimit;
        
        $productsPage = array_slice($filteredProducts, $offset, $this->moodLimit);

        return view('home.selectedMood', [ 
            'products' => $productsPage,
            'mood' => $mood,
            'gender' => $gender,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
        ]);
    }
}