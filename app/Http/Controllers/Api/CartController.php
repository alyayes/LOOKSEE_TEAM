<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CartsItems;
use App\Models\Produk;

class ApiCartController extends Controller
{
    /**
     * 1. GET CART (Lihat Keranjang)
     * Method: GET
     * URL: /api/cart
     */
    public function index()
    {
        $userId = Auth::id();

        if (!$userId) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        // Ambil cart user beserta data produknya
        $cartItems = CartsItems::where('user_id', $userId)->with('produk')->get();

        $formattedItems = [];
        $grandTotal = 0;

        foreach ($cartItems as $item) {
            if ($item->produk) {
                $subtotal = $item->produk->harga * $item->quantity;
                $grandTotal += $subtotal;

                $formattedItems[] = [
                    'cart_id'       => $item->id ?? $item->product_id, // ID unik item di cart
                    'product_id'    => $item->product_id,
                    'nama_produk'   => $item->produk->nama_produk,
                    // asset() penting agar URL gambar lengkap (http://...)
                    'gambar_produk' => asset('assets/images/produk-looksee/' . $item->produk->gambar_produk),
                    'harga'         => $item->produk->harga,
                    'quantity'      => $item->quantity,
                    'stock_sisa'    => $item->produk->stock, // Info stok buat validasi di Frontend
                    'subtotal'      => $subtotal
                ];
            }
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'items' => $formattedItems,
                'total_items' => count($formattedItems),
                'grand_total' => $grandTotal
            ]
        ], 200);
    }

    /**
     * 2. ADD TO CART (Tambah Barang)
     * Method: POST
     * URL: /api/cart/add
     * Body: product_id, quantity (optional)
     */
    public function addToCart(Request $request)
    {
        $userId = Auth::id();
        if (!$userId) return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);

        $id_produk = $request->input('product_id');
        $quantity = $request->input('quantity', 1);

        if (!$id_produk) {
            return response()->json(['status' => 'error', 'message' => 'Product ID wajib diisi'], 400);
        }

        // 1. Cek Produk
        $produk = Produk::where('id_produk', $id_produk)->first();
        if (!$produk) {
            return response()->json(['status' => 'error', 'message' => 'Produk tidak ditemukan'], 404);
        }

        // 2. Cek Keranjang Existing
        $cartItem = CartsItems::where('user_id', $userId)
                              ->where('product_id', $id_produk)
                              ->first();

        // 3. Validasi Stok
        $currentQty = $cartItem ? $cartItem->quantity : 0;
        $futureQty  = $currentQty + $quantity;

        if ($futureQty > $produk->stock) {
            return response()->json([
                'status' => 'error',
                'message' => 'Stok tidak cukup! Sisa stok hanya: ' . $produk->stock
            ], 400);
        }

        // 4. Simpan ke Database
        $message = '';
        if ($cartItem) {
            $cartItem->increment('quantity', $quantity);
            $message = 'Quantity produk berhasil ditambahkan';
        } else {
            CartsItems::create([
                'user_id'    => $userId,
                'product_id' => $id_produk,
                'quantity'   => $quantity
            ]);
            $message = 'Berhasil masuk keranjang!';
        }

        return response()->json([
            'status' => 'success',
            'message' => $message
        ], 200);
    }

    /**
     * 3. UPDATE QUANTITY (Edit Jumlah Langsung)
     * Method: POST
     * URL: /api/cart/update
     * Body: product_id, quantity
     */
    public function updateQuantity(Request $request)
    {
        $userId = Auth::id();
        $id_produk = $request->input('product_id');
        $new_quantity = $request->input('quantity');

        if (!$userId) return response()->json(['message' => 'Unauthorized'], 401);

        $produk = Produk::where('id_produk', $id_produk)->first();
        if (!$produk) return response()->json(['message' => 'Produk tidak ditemukan'], 404);

        if ($new_quantity > $produk->stock) {
             return response()->json(['status' => 'error', 'message' => 'Melebihi stok tersedia'], 400);
        }
        
        if ($new_quantity < 1) {
             return response()->json(['status' => 'error', 'message' => 'Quantity minimal 1'], 400);
        }

        $cartItem = CartsItems::where('user_id', $userId)
                              ->where('product_id', $id_produk)
                              ->first();

        if ($cartItem) {
            $cartItem->quantity = $new_quantity;
            $cartItem->save();
            return response()->json(['status' => 'success', 'message' => 'Quantity updated']);
        }

        return response()->json(['status' => 'error', 'message' => 'Item tidak ada di keranjang'], 404);
    }

    /**
     * 4. DELETE ITEM (Hapus Barang)
     * Method: POST
     * URL: /api/cart/delete
     * Body: product_id
     */
    public function deleteItem(Request $request)
    {
        $userId = Auth::id();
        $deleted = CartsItems::where('user_id', $userId)
                             ->where('product_id', $request->product_id)
                             ->delete();

        if ($deleted) {
            return response()->json(['status' => 'success', 'message' => 'Item dihapus dari keranjang']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Item tidak ditemukan'], 404);
        }
    }
}