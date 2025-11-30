<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StyleJournalController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrdersAdminController;
use App\Http\Controllers\ProductsAdminController;
use App\Http\Controllers\StyleJournalAdminController;
use App\Http\Controllers\DashboardAdminController;
use App\Http\Controllers\AnalyticsAdminController;
use App\Http\Controllers\UsersAdminController;
use App\Http\Controllers\TodaysOutfitAdminController; 
use App\Http\Controllers\PersonalizationController;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
// register POST
Route::post('/register', [AuthController::class, 'register']);

Route::get('/check-auth', function() {
    dd(Auth::id());
});

// Home
// 1. HOME PAGE 
Route::get('/home', [HomeController::class, 'index'])->name('homepage');
// 2. ROOT (/) - Mengalihkan ke Home Page

// 3. Home Selected Mood
Route::get('/mood', [HomeController::class, 'showMoodProducts'])->name('mood.products');

// 3. HOME SELECTED MOOD
Route::get('/mood', [HomeController::class, 'showMoodProducts'])->name('mood.products');
// Profile setting
Route::get('/settings/profile', [ProfileController::class, 'showSettings'])->name('profile.settings');
// Route untuk LOGOUT (Perlu Controller tersendiri di proyek nyata)
Route::get('/logout', function() {
    // Simulasi logout
    return redirect()->route('login')->with('info', 'Anda telah berhasil logout.');
})->name('logout');

// --- Style Journal (USER ROUTES) ---

// Rute Index (Daftar Jurnal)
Route::get('/style-journal', [StyleJournalController::class, 'index'])->name('journal.index');

// Rute CRUD 
Route::get('/style-journal/create', [StyleJournalController::class, 'create'])->name('journal.create');
Route::post('/style-journal', [StyleJournalController::class, 'store'])->name('journal.store');
Route::get('/style-journal/{style_journal}', [StyleJournalController::class, 'show'])->name('journal.show');
Route::get('/style-journal/{style_journal}/edit', [StyleJournalController::class, 'edit'])->name('journal.edit');
Route::put('/style-journal/{style_journal}', [StyleJournalController::class, 'update'])->name('journal.update');
Route::delete('/style-journal/{style_journal}', [StyleJournalController::class, 'destroy'])->name('journal.destroy');

// --- HALAMAN UTAMA & STATIS ---
Route::get('/', function () {
    return redirect()->route('community.trends');
})->name('home');


// --- MODUL KOMUNITAS ---
Route::prefix('community')->name('community.')->group(function () {
    Route::get('/trends', [CommunityController::class, 'trends'])->name('trends');
    Route::get('/todays-outfit', [CommunityController::class, 'todaysOutfit'])->name('todays-outfit');
    Route::get('/post/{id}', [CommunityController::class, 'showPostDetail'])->name('post.detail');
    Route::post('/post/{id}/like', [CommunityController::class, 'likePost'])->name('post.like');
    Route::post('/post/{id}/comment', [CommunityController::class, 'addComment'])->name('post.comment');
    Route::post('/post/{id}/share', [CommunityController::class, 'sharePost'])->name('post.share');
});


// --- MODUL PROFILE PENGGUNA ---
Route::prefix('profile')->name('profile.')->group(function () {
    Route::get('/', [ProfileController::class, 'index'])->name('index');
    Route::post('/upload', [ProfileController::class, 'uploadImage'])->name('upload');
    Route::get('/post/create', [ProfileController::class, 'showCreatePostForm'])->name('post.create');
    Route::post('/post', [ProfileController::class, 'storePost'])->name('post.store');
});


// --- ALUR CHECKOUT ---
Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/delete', [CartController::class, 'delete'])->name('cart.delete');
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'processCheckout'])->name('checkout.process');
Route::post('/checkout/save-address', [CheckoutController::class, 'saveTemporaryAddress'])->name('checkout.saveAddress');
Route::get('/payment/details', [PaymentController::class, 'showPaymentDetails'])->name('payment.details');
Route::get('/my-orders', [OrderController::class, 'list'])->name('orders.list');
Route::get('/orders/details/{order_id}', [OrderController::class, 'getOrderDetailsAjax'])->name('orders.details.ajax');

Route::post('/checkout/address/add', [CheckoutController::class, 'addAddress'])
    ->name('checkout.address.add');
Route::put('/checkout/address/update/{id}', [CheckoutController::class, 'updateAddress'])
    ->name('checkout.address.update');
Route::delete('/checkout/address/delete/{id}', [CheckoutController::class, 'deleteAddress'])
    ->name('checkout.address.delete');


// --- ROUTE DUMMY LAIN & REDIRECT ---
// Redirect URL lama dari header ke URL baru yang lebih rapi
Route::get('/trends', function () { return redirect()->route('community.trends'); })->name('trends');
// INI BAGIAN YANG DIPERBAIKI: 'komunitas.todaysOutfit' -> 'community.todays-outfit'
Route::get('/to', function () { return redirect()->route('community.todays-outfit'); })->name('to');
Route::get('/orders', function () { return redirect()->route('orders.list'); })->name('orders');

// Route dummy untuk link header yang belum dibuat
Route::get('/favorites', function () { return "Halaman Favorites (Dummy)"; })->name('favorites');
// Route::get('/settings', function () { return "Halaman Settings (Dummy)"; })->name('settings');
Route::get('/logout', function () { return "Proses Logout (Dummy)"; })->name('logout');

// --- HALAMAN FAVORIT ---
Route::prefix('favorites')->name('favorites.')->group(function () {
    // Menampilkan halaman utama My Favorites
    Route::get('/', [FavoriteController::class, 'index'])->name('index');
    
    // Endpoint AJAX untuk menghapus produk dari favorit
    Route::post('/delete', [FavoriteController::class, 'deleteFavorite'])->name('delete');
    
    // Endpoint AJAX untuk menambah produk ke keranjang dari halaman favorit
    Route::post('/add-to-cart', [FavoriteController::class, 'addToCart'])->name('addToCart');
});
Route::post('/cart/add/{id_produk}', [CartController::class, 'addToCart'])->name('cart.add');


// --- HALAMAN PRODUK ---
Route::prefix('products')->name('products.')->group(function () {
    // Halaman detail untuk satu produk
    Route::get('/{id}', [ProductController::class, 'show'])->name('detail');
    
    // Endpoint AJAX untuk menambah ke keranjang dari halaman detail
    Route::post('/add-to-cart', [ProductController::class, 'addToCart'])->name('addToCart');
    
    // Endpoint AJAX untuk menambah/menghapus favorit
    Route::post('/add-to-favorite', [ProductController::class, 'addToFavorite'])->name('addToFavorite');
});

/* --- RUTE PRODUK ADMIN (CRUD) --- */
Route::prefix('admin')->group(function () {
    
    // 1. [GET] Index: Menampilkan daftar produk
    // URI: /admin/products
    Route::get('/products', [ProductsAdminController::class, 'index'])->name('products.index'); 
    // 2. [GET] Create: Menampilkan form tambah produk
    // URI: /admin/products/add
Route::get('/products/add', [ProductsAdminController::class, 'add'])->name('products.add');
    // 3. [POST] Store: Memproses data form tambah
    // URI: /admin/products
    Route::post('/products', [ProductsAdminController::class, 'store'])->name('products.store');

    // 4. [GET] Edit: Menampilkan form edit produk berdasarkan ID
    // URI: /admin/products/{id}/edit
    Route::get('/products/{id}/edit', [ProductsAdminController::class, 'edit'])->name('products.edit');

    // 5. [PUT/PATCH] Update: Memproses data form edit
    // URI: /admin/products/{id}
    Route::put('/products/{id}', [ProductsAdminController::class, 'update'])->name('products.update');

    // 6. [DELETE] Destroy: Menghapus produk
    Route::delete('/products/{id}', [ProductsAdminController::class, 'destroy'])->name('products.destroy');
});

// logout
Route::post('/logout', function () {
    return redirect()->route('products.productsAdmin');
})->name('logout');
// Tambahkan di routes/web.php
Route::delete('/products/{id}', [ProductsAdminController::class, 'destroy'])->name('products.destroy');

// STYLE JOURNAL ADMIN
Route::resource('stylejournalAdmin', StyleJournalAdminController::class);

Route::resource('stylejournal', StyleJournalAdminController::class)->names([
    'index' => 'stylejournalAdmin.index',
    'create' => 'stylejournalAdmin.create',
    'store' => 'stylejournalAdmin.store',
    'show' => 'stylejournalAdmin.show',
    'edit' => 'stylejournalAdmin.edit',
    'update' => 'stylejournalAdmin.update',
    'destroy' => 'stylejournalAdmin.destroy',
]);
Route::get('/dashboard', [DashboardAdminController::class, 'index'])->name('dashboard.dashboardAdmin');

Route::prefix('admin')->group(function () {
    Route::get('/orders', [OrdersAdminController::class, 'index'])->name('admin.orders.index');
    Route::get('/orders/{order_id}', [OrdersAdminController::class, 'detail'])->name('admin.order.detail');
    Route::post('/orders/update-status', [OrdersAdminController::class, 'updateStatus'])
        ->name('admin.order.updateStatus');
    Route::get('/admin/orders/{order_id}', [OrdersAdminController::class, 'show'])
    ->name('admin.order.detail');

});


Route::get('/users-admin', [UsersAdminController::class, 'index'])->name('users-admin.usersAdmin');
Route::get('/toAdmin', [TodaysOutfitAdminController::class, 'index'])->name('toAdmin.toAdmin');

// Rute utama (/) akan mengecek status onboarding
Route::get('/', [PersonalizationController::class, 'showOnboarding']); 
// Route ini men-serve halaman Home jika onboarding sudah selesai
Route::get('/homepage', [HomeController::class, 'index'])->name('persona');

// ONBOARDING ROUTES
Route::get('/onboarding/personalize', [PersonalizationController::class, 'showOnboarding'])->name('onboarding.show');
Route::post('/onboarding/process', [PersonalizationController::class, 'processOnboarding'])->name('onboarding.process');