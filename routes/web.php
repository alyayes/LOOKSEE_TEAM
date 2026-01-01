<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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
use App\Http\Controllers\Api\ApiOrderController;
use App\Http\Controllers\Api\ApiPaymentController;

/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
*/

// Login
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Register
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Logout (final)
Route::get('/logout', function () {
    Auth::logout();
    return redirect()->route('login')->with('info', 'Anda berhasil logout.');
})->name('logout');


/*
|--------------------------------------------------------------------------
| USER PAGES
|--------------------------------------------------------------------------
*/

// Root redirect → Community Trends
Route::get('/', function () {
    return redirect()->route('community.trends');
})->name('home');

// Home
Route::get('/home', [HomeController::class, 'index'])->name('homepage');
Route::get('/mood', [HomeController::class, 'showMoodProducts'])->name('mood.products');

// Profile settings
Route::get('/settings/profile', [ProfileController::class, 'showSettings'])->name('profile.settings');
Route::post('/profile/update', [ProfileController::class, 'updateSettings'])->name('profile.update');


/*
|--------------------------------------------------------------------------
| STYLE JOURNAL
|--------------------------------------------------------------------------
*/

Route::get('/style-journal', [StyleJournalController::class, 'index'])->name('journal.index');
Route::get('/style-journal/{id}', [StyleJournalController::class, 'show'])->name('journal.show');


/*
|--------------------------------------------------------------------------
| COMMUNITY MODULE
|--------------------------------------------------------------------------
*/

Route::prefix('community')->name('community.')->group(function () {

    Route::get('/trends', [CommunityController::class, 'trends'])->name('trends');
    Route::get('/todays-outfit', [CommunityController::class, 'todaysOutfit'])->name('todays-outfit');

    Route::get('/post/{id}', [CommunityController::class, 'showPostDetail'])->name('post.detail');
    Route::post('/post/{id}/like', [CommunityController::class, 'toggleLike'])->name('post.like');
    Route::post('/post/{id}/comment', [CommunityController::class, 'addComment'])->name('post.comment');
    Route::post('/post/{id}/share', [CommunityController::class, 'sharePost'])->name('post.share');
});


/*
|--------------------------------------------------------------------------
| PROFILE MODULE
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

Route::prefix('profile')->name('profile.')->group(function () {
    
    Route::get('/', [ProfileController::class, 'index'])->name('index');

    Route::post('/upload', [ProfileController::class, 'uploadImage'])->name('upload');

    Route::get('/post/create', [ProfileController::class, 'showCreatePostForm'])->name('post.create');
    Route::post('/post', [ProfileController::class, 'storePost'])->name('post.store');
});
});

Route::middleware('auth')->group(function () {
    Route::get('/profile/post/{id}/edit', [ProfileController::class, 'showEditPostForm'])->name('profile.post.edit');
    Route::put('/profile/post/{id}', [ProfileController::class, 'updatePost'])->name('profile.post.update');
    Route::delete('/profile/post/{id}', [ProfileController::class, 'destroyPost'])->name('profile.post.destroy');
});


/*
|--------------------------------------------------------------------------
| CHECKOUT / CART / PAYMENT / ORDERS
|--------------------------------------------------------------------------
*/

Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/delete', [CartController::class, 'delete'])->name('cart.delete');

Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'processCheckout'])->name('checkout.process');
Route::post('/checkout/save-address', [CheckoutController::class, 'saveTemporaryAddress'])->name('checkout.saveAddress');

Route::middleware(['auth'])->group(function () {
    Route::delete('/checkout/address/{id}', [CheckoutController::class, 'deleteAddress'])->name('checkout.address.delete');
    
    Route::post('/checkout/address/add', [CheckoutController::class, 'addAddress'])->name('checkout.address.add');
    Route::put('/checkout/address/update/{id}', [CheckoutController::class, 'updateAddress']);
});

Route::get('/payment/details', [PaymentController::class, 'showPaymentDetails'])->name('payment.details');


Route::middleware(['auth'])->group(function () {
    Route::get('/my-orders', [OrderController::class, 'listOrders'])->name('orders.list');
    Route::get('/orders/details/{order_id}', [OrderController::class, 'getOrderDetailsAjax'])->name('orders.details.ajax');
});

/*
|--------------------------------------------------------------------------
| FAVORITES
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::prefix('favorites')->name('favorites.')->group(function () {
        Route::get('/', [FavoriteController::class, 'index'])->name('index');
        
        // TAMBAHKAN BARIS INI
        Route::post('/', [FavoriteController::class, 'store'])->name('store');
        
        Route::post('/delete', [FavoriteController::class, 'deleteFavorite'])->name('delete');
        Route::post('/add-to-cart', [FavoriteController::class, 'addToCart'])->name('addToCart');
    });
});

    // Endpoint AJAX untuk menghapus produk dari favorit
    Route::post('/delete', [FavoriteController::class, 'deleteFavorite'])->name('delete');

    // Endpoint AJAX untuk menambah produk ke keranjang dari halaman favorit
    Route::post('/add-to-cart', [FavoriteController::class, 'addToCart'])->name('addToCart');

Route::post('/cart/add/', [CartController::class, 'addToCart'])->name('cart.add');


// --- HALAMAN PRODUK ---
Route::prefix('products')->name('products.')->group(function () {
    // Halaman detail untuk satu produk
    Route::get('/{id}', [ProductController::class, 'show'])->name('detail');

    // Endpoint AJAX untuk menambah ke keranjang dari halaman detail
    Route::post('/add-to-cart', [ProductController::class, 'addToCart'])->name('addToCart');

    // Endpoint AJAX untuk menambah/menghapus favorit
    Route::post('/add-to-favorite', [ProductController::class, 'addToFavorite'])->name('addToFavorite');
});

// FAVORITE
Route::post('/favorite/add', [FavoriteController::class, 'store'])->name('favorite.add');

// CART
Route::post('/cart/add', [CartController::class, 'store'])->name('cart.add');

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
});

/*
|--------------------------------------------------------------------------
| PRODUCTS
|--------------------------------------------------------------------------
*/

Route::prefix('products')->name('products.')->group(function () {

    Route::get('/{id}', [ProductController::class, 'show'])->name('detail');

    Route::middleware('auth')->group(function () {
        Route::post('/add-to-cart', [ProductController::class, 'addToCart'])->name('addToCart');
        Route::post('/add-to-favorite', [ProductController::class, 'addToFavorite'])->name('addToFavorite');
    });
});


/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardAdminController::class, 'index'])->name('dashboard.dashboardAdmin');

    // Orders
    Route::get('/orders', [OrdersAdminController::class, 'index'])->name('admin.orders.index');
    Route::post('/orders/update-status', [OrdersAdminController::class, 'updateStatus'])->name('admin.order.updateStatus');
    Route::get('/orders/{order_id}', [OrdersAdminController::class, 'show'])->name('admin.order.detail');

    // Products
    Route::get('/products', [ProductsAdminController::class, 'index'])->name('products.index');
    Route::get('/products/add', [ProductsAdminController::class, 'add'])->name('products.add');
    Route::post('/products', [ProductsAdminController::class, 'store'])->name('products.store');
    Route::get('/products/{id}/edit', [ProductsAdminController::class, 'edit'])->name('products.edit');
    Route::put('/products/{id}', [ProductsAdminController::class, 'update'])->name('products.update');
    Route::delete('/products/{id}', [ProductsAdminController::class, 'destroy'])->name('products.destroy');

    Route::get('/analytics', [AnalyticsAdminController::class, 'index'])
    ->name('analyticsAdmin.analyticsAdmin');
});

// Other admin pages
Route::get('/users-admin', [UsersAdminController::class, 'index'])->name('users-admin.usersAdmin');
Route::get('/toAdmin', [TodaysOutfitAdminController::class, 'index'])->name('toAdmin.toAdmin');

Route::resource('stylejournalAdmin', StyleJournalAdminController::class);

/*
|--------------------------------------------------------------------------
| PERSONALIZATION / ONBOARDING
|--------------------------------------------------------------------------
*/

// Route::get('/onboarding/personalize', [PersonalizationController::class, 'showOnboarding'])->name('onboarding.show');
// Route::post('/onboarding/process', [PersonalizationController::class, 'processOnboarding'])->name('onboarding.process');

Route::get('/homepage', [HomeController::class, 'index'])->name('persona');
