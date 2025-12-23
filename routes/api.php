<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Import Controller API
use App\Http\Controllers\Api\ApiCartController;
use App\Http\Controllers\Api\ApiCheckoutController;
use App\Http\Controllers\Api\ApiOrderController;
use App\Http\Controllers\Api\ApiPaymentController;
use App\Http\Controllers\Api\ApiAuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Login & Logout
Route::post('/login', [ApiAuthController::class, 'login']);
Route::post('/logout', [ApiAuthController::class, 'logout']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// =================================================================
// ROUTE TOKO ONLINE (PUBLIC MODE / TANPA GROUP)
// =================================================================

// --- 1. CART (KERANJANG) ---
Route::get('/cart', [ApiCartController::class, 'index']);           // Lihat isi keranjang
Route::post('/cart/add', [ApiCartController::class, 'addToCart']);  // Tambah barang
Route::post('/cart/update', [ApiCartController::class, 'updateQuantity']); // Update jumlah
Route::post('/cart/delete', [ApiCartController::class, 'deleteItem']);     // Hapus barang

// --- 2. CHECKOUT ---
Route::get('/checkout/summary', [ApiCheckoutController::class, 'getCheckoutData']);
Route::post('/checkout/process', [ApiCheckoutController::class, 'processCheckout']);

// --- 3. ORDERS (RIWAYAT PESANAN) ---
Route::get('/orders', [ApiOrderController::class, 'listOrders']);
Route::get('/orders/{id}', [ApiOrderController::class, 'getOrderDetails']);

// --- 4. PAYMENT (PEMBAYARAN) ---
Route::get('/payment/details', [ApiPaymentController::class, 'showPaymentDetails']);