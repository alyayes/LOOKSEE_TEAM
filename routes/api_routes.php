<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\UsersController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    // User profile management
    Route::get('/profile', [UsersController::class, 'profile']);
    Route::put('/profile', [UsersController::class, 'updateProfile']);

    // Products CRUD
    Route::apiResource('products', ProductController::class);

    // Admin only routes
    Route::middleware('role:admin')->group(function () {
        Route::apiResource('users', UsersController::class)->except(['store']);
        Route::post('/users', [UsersController::class, 'store']); // Admin can create users
    });
});