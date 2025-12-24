<?php

// routes/api.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CommunityController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\AuthController;

// Route untuk Auth (Login & Register)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Route yang BUTUH login (Logout & Cek User)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
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