<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\UsersController;
use App\Http\Controllers\Api\CommunityController;
use App\Http\Controllers\Api\ProfileController;
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
});

// Public Routes (Bisa diakses tanpa login)
Route::get('/community/trends', [CommunityController::class, 'trends']);
Route::get('/community/todays-outfit', [CommunityController::class, 'todaysOutfit']);
Route::get('/community/post/{id}', [CommunityController::class, 'showPostDetail']); // Detail bisa publik, tapi status like-nya nanti false kalau gak login

Route::get('/profile', [ProfileController::class, 'index']);          // Lihat data diri sendiri
Route::post('/profile/update', [ProfileController::class, 'update']); // Edit data diri

// 2. Fitur CRUD Postingan
Route::post('/profile/post', [ProfileController::class, 'storePost']);       // Buat Post
Route::post('/profile/post/{id}', [ProfileController::class, 'updatePost']); // Edit Post
Route::delete('/profile/post/{id}', [ProfileController::class, 'destroyPost']); // Hapus Post

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
   

