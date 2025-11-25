<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;

class ProductsAdminController extends Controller
{
    private $storageUploadPath = 'storage/uploads/admin/produk_looksee/';
    

    private function initializeProducts()
    {
        return [
            [
                'id_produk' => 1, 'gambar_produk' => 't1.jpg', 
                'nama_produk' => 'Ribbonie',
                'deskripsi' => 'Knit Premium Cardi Atasan Wanita Rajut.', 'harga' => 35000.00, 'kategori' => 'Woman', 'mood' => 'Happy', 'stock' => 20,
                'preferensi' => 'Casual' 
            ],
            [
                'id_produk' => 2, 'gambar_produk' => 'man1.jpg', 
                'nama_produk' => 'Dobujack Shirt',
                'deskripsi' => 'Tshirt Stripe Snuffly Blue White Tees', 'harga' => 65000.00, 'kategori' => 'Man', 'mood' => 'Very Happy', 'stock' => 20,
                'preferensi' => 'Casual' 
            ],
            [
                'id_produk' => 3, 'gambar_produk' => '4.jpeg', 
                'nama_produk' => 'Kemeja Crop',
                'deskripsi' => 'Kemeja Crop Wanita', 'harga' => 65000.00, 'kategori' => 'Woman', 'mood' => 'Neutral', 'stock' => 20,
                'preferensi' => 'Y2K' 
            ],
            [
                'id_produk' => 4, 'gambar_produk' => 'download (20).jpg', 
                'nama_produk' => 'Virly Top',
                'deskripsi' => 'Korean Top Baju Knit Wanita Lengan Panjang Slim Fit.', 'harga' => 56000.00, 'kategori' => 'Woman', 'mood' => 'Sad', 'stock' => 20,
                'preferensi' => 'Minimalist' 
            ],
            [
                'id_produk' => 5, 'gambar_produk' => 'Metaphores.png', 
                'nama_produk' => 'Metaphores T-Shirt',
                'deskripsi' => 'Heavyweight T-shirt 16s Bone - Black', 'harga' => 135000.00, 'kategori' => 'Man', 'mood' => 'Very Sad', 'stock' => 20,
                'preferensi' => 'Streetwear' 
            ],
            [
                'id_produk' => 6, 'gambar_produk' => 'kemeja hitam.png', 
                'nama_produk' => 'Kemeja Rayon Polos',
                'deskripsi' => 'Kemeja Polos Lengan Panjang Rayon Organik', 'harga' => 57000.00, 'kategori' => 'Man', 'mood' => 'Very Sad', 'stock' => 20,
                'preferensi' => 'Minimalist' 
            ],
            [
                'id_produk' => 7, 'gambar_produk' => 'shenning.jpg', 
                'nama_produk' => 'Shenning Knit',
                'deskripsi' => 'Knitwear Round Neck Polos Lengan Kombinasi Renda', 'harga' => 127000.00, 'kategori' => 'Woman', 'mood' => 'Very Happy', 'stock' => 18,
                'preferensi' => 'Coquette' 
            ],
            [
                'id_produk' => 8, 'gambar_produk' => 'russ polo.png', 
                'nama_produk' => 'Russ Poloshirt',
                'deskripsi' => 'Poloshirt Oversize Rugby Tangan Pendek Gainner', 'harga' => 174000.00, 'kategori' => 'Man', 'mood' => 'Neutral', 'stock' => 20,
                'preferensi' => 'Sporty' 
            ],
            [
                'id_produk' => 9, 'gambar_produk' => 'rowndivision.jpg', 
                'nama_produk' => 'Rown Division Polo',
                'deskripsi' => 'Polo Shirt Oversize - Kaos Wangky Colder Navy', 'harga' => 129000.00, 'kategori' => 'Man', 'mood' => 'Sad', 'stock' => 20,
                'preferensi' => 'Casual' 
            ],
            [
                'id_produk' => 10, 'gambar_produk' => 'madless.jpg', 
                'nama_produk' => 'Madless Shirt',
                'deskripsi' => 'Kemeja Lengan Pendek Stripe Boxy Stripe Light Blue', 'harga' => 107000.00, 'kategori' => 'Man', 'mood' => 'Happy', 'stock' => 20,
                'preferensi' => 'Vintage' 
            ],
            [
                'id_produk' => 11, 'gambar_produk' => '1.jpeg', 
                'nama_produk' => 'Kemeja Puff Chin',
                'deskripsi' => 'Kemeja Lengan Panjang Croptop', 'harga' => 65000.00, 'kategori' => 'Woman', 'mood' => 'Neutral', 'stock' => 20,
                'preferensi' => 'Coquette' 
            ],
            [
                'id_produk' => 12, 'gambar_produk' => 'download (18).jpg', 
                'nama_produk' => 'Jeans Highwaist',
                'deskripsi' => 'Celana Kulot Jeans Highwaist', 'harga' => 118000.00, 'kategori' => 'Woman', 'mood' => 'Neutral', 'stock' => 20,
                'preferensi' => 'Casual' 
            ],
            [
                'id_produk' => 13, 'gambar_produk' => 'oro.png', 
                'nama_produk' => 'Oro Pants',
                'deskripsi' => 'Kulot Jeans Highwaist', 'harga' => 40000.00, 'kategori' => 'Woman', 'mood' => 'Neutral', 'stock' => 20,
                'preferensi' => 'Casual' 
            ],
            [
                'id_produk' => 14, 'gambar_produk' => 'jasmine.png', 
                'nama_produk' => 'Jasmine Blouse',
                'deskripsi' => 'Blouse Katun Poplin', 'harga' => 65000.00, 'kategori' => 'Woman', 'mood' => 'Very Happy', 'stock' => 20,
                'preferensi' => 'Elegant' 
            ],
            [
                'id_produk' => 15, 'gambar_produk' => 'celia.png', 
                'nama_produk' => 'Celia Blouse',
                'deskripsi' => 'Blouse Embroidery Linen', 'harga' => 12000.00, 'kategori' => 'Woman', 'mood' => 'Neutral', 'stock' => 20,
                'preferensi' => 'Elegant' 
            ],
            [
                'id_produk' => 16, 'gambar_produk' => 'peony.png', 
                'nama_produk' => 'Peony Blouse',
                'deskripsi' => 'Coquette Blouse', 'harga' => 145000.00, 'kategori' => 'Woman', 'mood' => 'Happy', 'stock' => 20,
                'preferensi' => 'Coquette' 
            ],
            [
                'id_produk' => 17, 'gambar_produk' => 'ribonia.png', 
                'nama_produk' => 'Ribonia',
                'deskripsi' => 'Ribonia Shirt Katun Poly Lembut', 'harga' => 30000.00, 'kategori' => 'Woman', 'mood' => 'Very Happy', 'stock' => 20,
                'preferensi' => 'Casual' 
            ],
            [
                'id_produk' => 18, 'gambar_produk' => 'hool.png', 
                'nama_produk' => 'Hooligans Sweater',
                'deskripsi' => 'Sweater Crewneck Bold Linea - Dusty Blue', 'harga' => 162000.00, 'kategori' => 'Man', 'mood' => 'Very Happy', 'stock' => 19,
                'preferensi' => 'Streetwear' 
            ],
            [
                'id_produk' => 19, 'gambar_produk' => 'unicloth.png', 
                'nama_produk' => 'Blouse Katbol',
                'deskripsi' => 'Blouse Katbol Bordir Elegan', 'harga' => 152000.00, 'kategori' => 'Woman', 'mood' => 'Neutral', 'stock' => 20,
                'preferensi' => 'Elegant' 
            ],
            [
                'id_produk' => 20, 'gambar_produk' => '3.jpeg', 
                'nama_produk' => 'Red Shirt',
                'deskripsi' => 'Kemeja merah lengan panjang', 'harga' => 65000.00, 'kategori' => 'Woman', 'mood' => 'Sad', 'stock' => 20,
                'preferensi' => 'Minimalist' 
            ],
            [
                'id_produk' => 21, 'gambar_produk' => 'slimfit.jpg', 
                'nama_produk' => 'Celana Slimfit',
                'deskripsi' => 'Celana formal pria slimfit', 'harga' => 50000.00, 'kategori' => 'Man', 'mood' => 'Very Sad', 'stock' => 20,
                'preferensi' => 'Elegant' 
            ],
            [
                'id_produk' => 22, 'gambar_produk' => 'cardi ketupat.jpg', 
                'nama_produk' => 'V Neck Cardi Biru',
                'deskripsi' => 'V Neck Ketupat Cardigan Biru', 'harga' => 80000.00, 'kategori' => 'Woman', 'mood' => 'Sad', 'stock' => 20,
                'preferensi' => 'Vintage' 
            ],
            [
                'id_produk' => 23, 'gambar_produk' => 'cardi biru.jpg', 
                'nama_produk' => 'Cardi Rajut Snowy',
                'deskripsi' => 'Cardigan rajut crop motif kotak-kotak', 'harga' => 50000.00, 'kategori' => 'Woman', 'mood' => 'Very Happy', 'stock' => 20,
                'preferensi' => 'Y2K' 
            ],
            [
                'id_produk' => 24, 'gambar_produk' => 'download (47).jpg', 
                'nama_produk' => 'Celana Cargo',
                'deskripsi' => 'Celana cargo oversize Baggy Pants Pria', 'harga' => 70000.00, 'kategori' => 'Man', 'mood' => 'Very Sad', 'stock' => 20,
                'preferensi' => 'Streetwear' 
            ],
            [
                'id_produk' => 25, 'gambar_produk' => 'Widejeans.jpg', 
                'nama_produk' => 'Celana Jeans Wide Leg',
                'deskripsi' => 'Jeans Wide Leg Whisker Skena Black Whisker', 'harga' => 200000.00, 'kategori' => 'Man', 'mood' => 'Very Sad', 'stock' => 20,
                'preferensi' => 'Streetwear' 
            ],
            [
                'id_produk' => 26, 'gambar_produk' => 'download (46).jpg', 
                'nama_produk' => 'Celana Chino',
                'deskripsi' => 'Celana Chino Panjang Reguler/Standar Pria', 'harga' => 150000.00, 'kategori' => 'Man', 'mood' => 'Neutral', 'stock' => 20,
                'preferensi' => 'Casual' 
            ],
            [
                'id_produk' => 27, 'gambar_produk' => 'satin.jpg', 
                'nama_produk' => 'Rok Satin',
                'deskripsi' => 'Satin Long Skirt', 'harga' => 50000.00, 'kategori' => 'Woman', 'mood' => 'Sad', 'stock' => 20,
                'preferensi' => 'Elegant' 
            ],
            [
                'id_produk' => 28, 'gambar_produk' => 'tutu.jpg', 
                'nama_produk' => 'Rok Tutu',
                'deskripsi' => 'Rok Tutu Panjang', 'harga' => 40000.00, 'kategori' => 'Woman', 'mood' => 'Neutral', 'stock' => 20,
                'preferensi' => 'Coquette' 
            ],
            [
                'id_produk' => 29, 'gambar_produk' => 'rok pita.jpg', 
                'nama_produk' => 'Rok Serut Pita',
                'deskripsi' => 'Honey Skirt Tali Serut / Ashelia Skirt', 'harga' => 38000.00, 'kategori' => 'Woman', 'mood' => 'Very Happy', 'stock' => 20,
                'preferensi' => 'Coquette' 
            ],
            [
                'id_produk' => 30, 'gambar_produk' => 'rok jeans.jpg', 
                'nama_produk' => 'Rok Jeans Theana',
                'deskripsi' => 'Rok Jeans Panjang Span', 'harga' => 115000.00, 'kategori' => 'Woman', 'mood' => 'Happy', 'stock' => 20,
                'preferensi' => 'Casual' 
            ],
            [
                'id_produk' => 31, 'gambar_produk' => 'dritto.jpg', 
                'nama_produk' => 'Jaket Boxy',
                'deskripsi' => 'Work Jaket Boxy - Jacket Crop "Liscio".', 'harga' => 155000.00, 'kategori' => 'Man', 'mood' => 'Very Sad', 'stock' => 20,
                'preferensi' => 'Streetwear' 
            ],
            [
                'id_produk' => 32, 'gambar_produk' => 'kemeja casual.jpg', 
                'nama_produk' => 'Kemeja Katun Casual',
                'deskripsi' => 'Kemeja Pita Lengan Panjang Casual Polos.', 'harga' => 80000.00, 'kategori' => 'Man', 'mood' => 'Very Happy', 'stock' => 20,
                'preferensi' => 'Casual' 
            ],
            [
                'id_produk' => 33, 'gambar_produk' => 'ijo.jpg', 
                'nama_produk' => 'Kemeja Greentea',
                'deskripsi' => 'Kemeja Lengan Pendek', 'harga' => 50000.00, 'kategori' => 'Woman', 'mood' => 'Sad', 'stock' => 20,
                'preferensi' => 'Minimalist' 
            ],
            [
                'id_produk' => 34, 'gambar_produk' => 'man2.jpg', 
                'nama_produk' => 'Kaos Blacky',
                'deskripsi' => 'Kaos Hitam Lengan Pendek', 'harga' => 55000.00, 'kategori' => 'Man', 'mood' => 'Very Sad', 'stock' => 20,
                'preferensi' => 'Minimalist' 
            ],
            [
                'id_produk' => 35, 'gambar_produk' => 'kemeja kelvin.jpg', 
                'nama_produk' => 'Kemeja Kelvin',
                'deskripsi' => 'Kemeja Polos Lengan Panjang Slim Fit', 'harga' => 54000.00, 'kategori' => 'Man', 'mood' => 'Happy', 'stock' => 20,
                'preferensi' => 'Elegant' 
            ],
            [
                'id_produk' => 36, 'gambar_produk' => 'loose pants.jpg', 
                'nama_produk' => 'Loose Pants',
                'deskripsi' => 'Celana Panjang Loose Pants Pria - Vana Chino', 'harga' => 60000.00, 'kategori' => 'Man', 'mood' => 'Neutral', 'stock' => 20,
                'preferensi' => 'Streetwear' 
            ],
            [
                'id_produk' => 37, 'gambar_produk' => 'nike.png', 
                'nama_produk' => 'Sepatu Nike',
                'deskripsi' => 'Sepatu Nike SB Pogo Plus dalam kombinasi warna hitam dan putih...', 'harga' => 550000.00, 'kategori' => 'Man', 'mood' => 'Sad', 'stock' => 20,
                'preferensi' => 'Sporty' 
            ],
            [
                'id_produk' => 38, 'gambar_produk' => 'slingbag.png', 
                'nama_produk' => 'Ladiesbag',
                'deskripsi' => 'Tas bahu wanita dengan desain stylish dan praktis...', 'harga' => 141000.00, 'kategori' => 'Woman', 'mood' => 'Neutral', 'stock' => 20,
                'preferensi' => 'Casual' 
            ],
            [
                'id_produk' => 39, 'gambar_produk' => 'sheen silk.png', 
                'nama_produk' => 'Sheen Pashmina Silk',
                'deskripsi' => 'Pashmina premium dengan bahan berkualitas tinggi...', 'harga' => 132500.00, 'kategori' => 'Woman', 'mood' => 'Neutral', 'stock' => 19,
                'preferensi' => 'Elegant' 
            ],
            [
                'id_produk' => 40, 'gambar_produk' => 'hyubin kaos.png', 
                'nama_produk' => 'Hyubin Kaos Polos',
                'deskripsi' => 'Kaos polos oversize dengan bahan heavyweight cotton 16s...', 'harga' => 83000.00, 'kategori' => 'Man', 'mood' => 'Very Sad', 'stock' => 20,
                'preferensi' => 'Minimalist' 
            ],
            [
                'id_produk' => 41, 'gambar_produk' => 'grizzly rompi.png', 
                'nama_produk' => 'Grizzly Rompi',
                'deskripsi' => 'Rompi pria dewasa dengan desain polos satu warna...', 'harga' => 80000.00, 'kategori' => 'Man', 'mood' => 'Very Sad', 'stock' => 20,
                'preferensi' => 'Minimalist' 
            ],
            [
                'id_produk' => 42, 'gambar_produk' => 'cargo jeans.png', 
                'nama_produk' => 'Cargo Loose Jeans',
                'deskripsi' => 'Jeans longgar dengan gaya cargo dan potongan wide leg...', 'harga' => 140000.00, 'kategori' => 'Man', 'mood' => 'Sad', 'stock' => 19,
                'preferensi' => 'Streetwear' 
            ],
            [
                'id_produk' => 43, 'gambar_produk' => 'highwaisted denim.jpg', 
                'nama_produk' => 'Gia Jeans Highwaist',
                'deskripsi' => 'Jeans denim highwaist dengan potongan wide leg...', 'harga' => 349000.00, 'kategori' => 'Woman', 'mood' => 'Sad', 'stock' => 20,
                'preferensi' => 'Casual' 
            ],
            [
                'id_produk' => 44, 'gambar_produk' => 'jeans boyfriend.jpeg', 
                'nama_produk' => 'Jeans Boyfriend',
                'deskripsi' => 'Basic Highwaist Boyfriend.', 'harga' => 119000.00, 'kategori' => 'Woman', 'mood' => 'Neutral', 'stock' => 12,
                'preferensi' => 'Casual' 
            ],
            [
                'id_produk' => 45, 'gambar_produk' => 'black hijab.jpg', 
                'nama_produk' => 'Lozy Square Hijab',
                'deskripsi' => 'Polly Cotton Hijab Square.', 'harga' => 34000.00, 'kategori' => 'Woman', 'mood' => 'Very Sad', 'stock' => 20,
                'preferensi' => 'Minimalist' 
            ],
            [
                'id_produk' => 46, 'gambar_produk' => 'Short Sleeve Tee.jpg', 
                'nama_produk' => 'Basic T-Shirt',
                'deskripsi' => 'RegulerFit Premium Cotton Combed.', 'harga' => 33000.00, 'kategori' => 'Woman', 'mood' => 'Neutral', 'stock' => 18,
                'preferensi' => 'Minimalist' 
            ],
            [
                'id_produk' => 47, 'gambar_produk' => 'Sleeve Stripes.jpg', 
                'nama_produk' => 'Executive Sleeve Stripes',
                'deskripsi' => 'Kemeja lengan panjang bergaris dengan potongan oversized...', 'harga' => 220000.00, 'kategori' => 'Woman', 'mood' => 'Neutral', 'stock' => 19,
                'preferensi' => 'Casual' 
            ],
            [
                'id_produk' => 48, 'gambar_produk' => 'chunky.jpg', 
                'nama_produk' => 'Chunky Patent Oxfords',
                'deskripsi' => 'Sepatu pria dengan sentuhan modern yang menonjolkan...', 'harga' => 450000.00, 'kategori' => 'Man', 'mood' => 'Sad', 'stock' => 10,
                'preferensi' => 'Vintage' 
            ],
            [
                'id_produk' => 49, 'gambar_produk' => 'Denim Overshirt.jpeg', 
                'nama_produk' => 'Denim Overshirt',
                'deskripsi' => 'Kemeja denim pria dengan potongan oversized yang santai...', 'harga' => 180000.00, 'kategori' => 'Man', 'mood' => 'Neutral', 'stock' => 3,
                'preferensi' => 'Streetwear' 
            ],
            [
                'id_produk' => 50, 'gambar_produk' => 'Kacamata Retro.jpeg', 
                'nama_produk' => 'Kacamata Retro',
                'deskripsi' => 'Kacamata hitam dengan desain persegi panjang yang ramping dan sentuhan retro.', 'harga' => 85000.00, 'kategori' => 'Man', 'mood' => 'Sad', 'stock' => 20,
                'preferensi' => 'Vintage' 
            ],
            [
                'id_produk' => 51, 'gambar_produk' => 'black wide jeans.jpeg', 
                'nama_produk' => 'Black Wide Jeans',
                'deskripsi' => 'Jeans hitam dengan washed look yang memberikan sentuhan vintage dan distressed...', 'harga' => 250000.00, 'kategori' => 'Man', 'mood' => 'Sad', 'stock' => 15,
                'preferensi' => 'Vintage' 
            ],
            [
                'id_produk' => 52, 'gambar_produk' => 'Basic Top.jpeg', 
                'nama_produk' => 'Basic Top',
                'deskripsi' => 'Kaos basic hitam dengan potongan pas badan dan kerah square-neck yang modern.', 'harga' => 75000.00, 'kategori' => 'Woman', 'mood' => 'Sad', 'stock' => 14,
                'preferensi' => 'Minimalist' 
            ],
            [
                'id_produk' => 53, 'gambar_produk' => 'Classic Flare Jeans.jpeg', 
                'nama_produk' => 'Classic Flare Jeans',
                'deskripsi' => 'Jeans flare klasik dengan warna biru medium (mid-wash) yang serbaguna.', 'harga' => 210000.00, 'kategori' => 'Woman', 'mood' => 'Neutral', 'stock' => 25,
                'preferensi' => 'Vintage' 
            ],
        ];
    }

    private function getProductsFromSession()
    {
        if (!Session::has('products_data') || count(Session::get('products_data')) < 50) {
            $initialProducts = $this->initializeProducts();
            Session::put('products_data', $initialProducts);
            return $initialProducts;
        }
        return Session::get('products_data');
    }


    public function index()
    {
        $initialProducts = $this->initializeProducts();
        Session::put('products_data', $initialProducts);

        $products = Session::get('products_data'); 

        return view('productsAdmin.productsAdmin', compact('products'));
    }

    
    public function add()
{
    return view('productsAdmin.addProductAdmin');
}


    public function store(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'harga' => 'required|numeric|min:0',
            'kategori' => 'required|string|max:255',
            'mood' => 'required|string|max:255',
            'stock' => 'required|integer|min:0', 
            'platform' => 'required|string|max:100',
            'link_produk' => 'required|url|max:255',
            'gambar_produk' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048', 
        ]);

        $gambar_produk_filename = null;
        if ($request->hasFile('gambar_produk')) {
            $path = $request->file('gambar_produk')->store($this->storageUploadPath, 'public');
            $gambar_produk_filename = basename($path);
        }

        $products = $this->getProductsFromSession();
        $newId = count($products) > 0 ? end($products)['id_produk'] + 1 : 1;

        $newProduct = [
            'id_produk' => $newId,
            'gambar_produk' => $gambar_produk_filename,
            'nama_produk' => $request->nama_produk,
            'deskripsi' => $request->deskripsi,
            'harga' => (float)$request->harga,
            'kategori' => $request->kategori,
            'mood' => $request->mood,
            'platform' => $request->platform,
            'stock' => (int)$request->stock, 
        ];

        $products[] = $newProduct;
        Session::put('products_data', $products); 

        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan (Dummy)!');
    }
    
    private function findProductById($id)
    {
        $products = $this->getProductsFromSession();
        $id = (int) $id;

        foreach ($products as $product) {
            if ($product['id_produk'] === $id) {
                return $product;
            }
        }
        return null;
    }

    public function edit($id)
    {
        $product = $this->findProductById($id);

        if (!$product) {
            return redirect()->route('products.index')->with('error', 'Produk tidak ditemukan.');
        }

        return view('productsAdmin.editProductAdmin', compact('product'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'harga' => 'required|numeric|min:0',
            'kategori' => 'required|string|max:255',
            'mood' => 'required|string|max:255',
            'stock' => 'required|integer|min:0',
            'gambar_produk' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048', 
        ]);

        $products = $this->getProductsFromSession();
        $id = (int) $id;
        $productIndex = -1;

        foreach ($products as $index => $product) {
            if ($product['id_produk'] === $id) {
                $productIndex = $index;
                break;
            }
        }

        if ($productIndex === -1) {
            return redirect()->route('products.index')->with('error', 'Produk tidak ditemukan.');
        }
        
        $gambarLama = $products[$productIndex]['gambar_produk'];
        $image_path = $gambarLama;

        if ($request->hasFile('gambar_produk')) {
            $path = $request->file('gambar_produk')->store($this->storageUploadPath, 'public');
            $image_path = basename($path);
            
            $old_file_path = $this->storageUploadPath . '/' . $gambarLama;

            if ($gambarLama && Storage::disk('public')->exists($old_file_path) && $gambarLama !== $image_path) {
                 Storage::disk('public')->delete($old_file_path);
            }
        }

        $products[$productIndex] = array_merge($products[$productIndex], [
            'gambar_produk' => $image_path,
            'nama_produk' => $request->nama_produk,
            'deskripsi' => $request->deskripsi,
            'harga' => (float)$request->harga,
            'kategori' => $request->kategori,
            'mood' => $request->mood,
            'stock' => (int)$request->stock,
        ]);

        Session::put('products_data', $products);

        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui (Dummy)!');
    }

    public function destroy($id)
    {
        $products = $this->getProductsFromSession();
        $id = (int) $id;
        $initialCount = count($products);
        $deleted_product = null;

        foreach ($products as $product) {
            if ($product['id_produk'] === $id) {
                $deleted_product = $product;
                break;
            }
        }

        if ($deleted_product && $deleted_product['gambar_produk']) {
             $file_path = $this->storageUploadPath . '/' . $deleted_product['gambar_produk'];
             if (Storage::disk('public')->exists($file_path)) {
                 Storage::disk('public')->delete($file_path);
             }
        }

        $products = array_filter($products, function ($product) use ($id) {
            return $product['id_produk'] !== $id;
        });

        Session::put('products_data', array_values($products));

        if (count($products) < $initialCount) {
            return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus (Dummy).');
        } else {
            return redirect()->route('products.index')->with('error', 'Produk gagal dihapus.');
        }
    }
}