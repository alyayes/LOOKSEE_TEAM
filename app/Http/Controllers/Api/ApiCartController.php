<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Jangan lupa ini
use App\Models\CartsItems;
use App\Models\Produk;

class ApiCartController extends Controller
{

    public function index(Request $request)
    {
      
        $userId = Auth::id(); 

        $cartItems = CartsItems::where('user_id', $userId)->with('produk')->get();

        $formattedItems = [];
        $grandTotal = 0;

        foreach ($cartItems as $item) {
            if ($item->produk) {
                $subtotal = $item->produk->harga * $item->quantity;
                $grandTotal += $subtotal;

                $formattedItems[] = [
                    'cart_id'       => $item->id ?? $item->product_id,
                    'product_id'    => $item->product_id,
                    'nama_produk'   => $item->produk->nama_produk,
                    'gambar_produk' => asset('assets/images/produk-looksee/' . $item->produk->gambar_produk),
                    'harga'         => $item->produk->harga,
                    'quantity'      => $item->quantity,
                    'stock_sisa'    => $item->produk->stock,
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
     * 2. ADD TO CART
     */
    public function addToCart(Request $request)
    {
        // --- PERBAIKAN DISINI ---
        $userId = Auth::id(); 

        $id_produk = $request->input('product_id'); // Pastikan key di Postman 'product_id'
        $quantity = $request->input('quantity', 1);

        $produk = Produk::where('id_produk', $id_produk)->first();
        if (!$produk) {
            return response()->json(['status' => 'error', 'message' => 'Produk tidak ditemukan'], 404);
        }

        $cartItem = CartsItems::where('user_id', $userId)
                              ->where('product_id', $id_produk)
                              ->first();

        // Validasi Stok
        $currentQty = $cartItem ? $cartItem->quantity : 0;
        $futureQty  = $currentQty + $quantity;

        if ($futureQty > $produk->stock) {
            return response()->json([
                'status' => 'error',
                'message' => 'Stok tidak cukup! Sisa stok hanya: ' . $produk->stock
            ], 400);
        }

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
     * 3. UPDATE QUANTITY
     */
    public function updateQuantity(Request $request)
    {
        // --- PERBAIKAN DISINI ---
        $userId = Auth::id();

        $id_produk = $request->input('product_id');
        $new_quantity = $request->input('quantity');

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
     * 4. DELETE ITEM
     */
    public function deleteItem(Request $request)
    {
        // --- PERBAIKAN DISINI ---
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