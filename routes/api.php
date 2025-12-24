<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\UsersController;
use App\Http\Controllers\Api\ApiCartController;
use App\Http\Controllers\Api\ApiCheckoutController;
use App\Http\Controllers\Api\ApiOrderController;
use App\Http\Controllers\Api\ApiPaymentController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    // User profile management
    Route::get('/profile', [UsersController::class, 'profile']);
    Route::put('/profile', [UsersController::class, 'updateProfile']);

    // Products CRUD
    Route::apiResource('produk', ProductController::class);

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
   
});

