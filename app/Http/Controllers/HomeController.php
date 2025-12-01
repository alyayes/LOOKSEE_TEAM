<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;

class HomeController extends Controller
{
    private $limit = 5; 
    private $moodLimit = 10;

    // Method Home Page (URL: /home)
    public function index(Request $request)
    {
        // GANTI DUMMY KE DATABASE
        $allProducts = Produk::orderByDesc('id_produk')->get();

        // --- Produk Wanita ---
        $productsWoman = $allProducts->filter(function($p) {
            return in_array($p->kategori, ['Wanita', 'Woman']);
        })->values()->all();
        
        $pageWoman = $request->query('page_woman', 1);
        $offsetWoman = ($pageWoman - 1) * $this->limit;
        
        $productsWomanPage = array_slice($productsWoman, $offsetWoman, $this->limit);
        $totalPagesWoman = ceil(count($productsWoman) / $this->limit);

        $productsMan = $allProducts->filter(function($p) {
            return in_array($p->kategori, ['Pria', 'Man']);
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
        // GANTI DUMMY KE DATABASE
        $allProducts = Produk::orderByDesc('id_produk')->get();

        $mood = strtolower($request->query('mood', 'netral'));
        $gender = strtolower($request->query('gender', ''));
        $currentPage = $request->query('page', 1);

        // --- 2. Filter Mood + Gender ---
        $filteredProducts = $allProducts->filter(function($p) use ($mood, $gender) {

            $isMoodMatch = str_contains(strtolower($p->mood), $mood);

            $kategori = strtolower($p->kategori);
            $isGenderMatch = (!$gender) ||
                             ($gender === 'men' && in_array($kategori, ['pria', 'man'])) ||
                             ($gender === 'women' && in_array($kategori, ['wanita', 'woman']));

            return $isMoodMatch && $isGenderMatch;
        })->values()->all();

        // --- 3. Pagination Manual ---
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
