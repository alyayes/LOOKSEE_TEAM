<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Tambahan penting buat ambil ID user login
use App\Models\CartsItems;
use App\Models\Produk;

class CartController extends Controller
{
    public function index()
    {
        // AMAN: Ambil cart hanya punya user yang sedang login
        $userId = Auth::id(); 
        $data_cart = CartsItems::with('produk')->where('user_id', $userId)->get();
        
        $cart_items = [];
        $total_selected_price = 0;

        foreach ($data_cart as $item) {
            if ($item->produk) {
                // Hitung total (opsional, tergantung logic frontend kamu mau hitung selected atau all)
                $total_selected_price += $item->produk->harga * $item->quantity;

                $cart_items[$item->product_id] = [
                    'id_produk'     => $item->product_id, 
                    'nama_produk'   => $item->produk->nama_produk,
                    'harga'         => $item->produk->harga,
                    'stock'         => $item->produk->stock,
                    'quantity'      => $item->quantity,
                    'gambar_produk' => $item->produk->gambar_produk
                ];
            }
        }

        $total_products = count($cart_items);

        return view('cart.cart', compact('cart_items', 'total_selected_price', 'total_products'));
    }

    // FUNGSI UTAMA ADD TO CART
    // Diubah menerima Request, bukan langsung $id_produk karena route-nya POST tanpa parameter URL
    public function addToCart(Request $request)
    {
        // Cek input, bisa 'id_produk' atau 'product_id' tergantung kiriman AJAX/Form kamu
        $id_produk = $request->input('id_produk') ?? $request->input('product_id'); 
        $quantity = $request->input('quantity', 1); // Default 1 kalau tidak dikirim

        if (!$id_produk) {
            return response()->json(['status' => 'error', 'message' => 'Product ID is required'], 400);
        }

        $produkExists = Produk::where('id_produk', $id_produk)->exists();
        
        if (!$produkExists) {
            return response()->json([
                'status' => 'error',
                'message' => 'Produk tidak ditemukan!'
            ], 404);
        }

        $userId = Auth::id(); // Pakai Auth ID

        // Cek apakah user ini sudah punya produk ini di cart
        $cek = CartsItems::where('user_id', $userId)
                         ->where('product_id', $id_produk)
                         ->first();

        if ($cek) {
            $cek->increment('quantity', $quantity);
        } else {
            CartsItems::create([
                'user_id'    => $userId, 
                'product_id' => $id_produk,
                'quantity'   => $quantity
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil masuk keranjang!'
        ]);
    }

    // TAMBAHAN: Karena di web.php baris 195 ada route yang manggil function 'store'
    public function store(Request $request)
    {
        // Kita oper saja ke fungsi addToCart biar logic-nya satu pintu
        return $this->addToCart($request);
    }

    public function update(Request $req)
    {
        $userId = Auth::id();
        
        // Update cara lebih aman: update existing record daripada delete-create
        $cartItem = CartsItems::where('user_id', $userId)
                              ->where('product_id', $req->id_produk)
                              ->first();

        if ($cartItem) {
            $cartItem->quantity = $req->quantity;
            $cartItem->save();
        } else {
            // Kalau entah kenapa barangnya ga ada, create baru
             CartsItems::create([
                'user_id'    => $userId,
                'product_id' => $req->id_produk,
                'quantity'   => $req->quantity
            ]);
        }

        return response()->json(['status' => 'success']);
    }

    public function delete(Request $req)
    {
        $userId = Auth::id();

        // Hapus hanya punya user yang login
        CartsItems::where('user_id', $userId)
                  ->where('product_id', $req->id_produk)
                  ->delete();

        return redirect()->route('cart'); // Redirect pakai route name lebih aman
    }
}