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
use App\Http\Controllers\PersonalizationController;

// --- AUTHENTICATION ---
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// --- PUBLIC ROUTES ---
Route::get('/', function () {
    return redirect()->route('community.trends');
})->name('home');

Route::get('/home', [HomeController::class, 'index'])->name('homepage');
Route::get('/mood', [HomeController::class, 'showMoodProducts'])->name('mood.products');

// --- COMMUNITY ---
Route::prefix('community')->name('community.')->group(function () {
    Route::get('/trends', [CommunityController::class, 'trends'])->name('trends');
    Route::get('/todays-outfit', [CommunityController::class, 'todaysOutfit'])->name('todays-outfit');
    Route::get('/post/{id}', [CommunityController::class, 'showPostDetail'])->name('post.detail');
    Route::post('/post/{id}/like', [CommunityController::class, 'toggleLike'])->name('post.like');
    Route::post('/post/{id}/comment', [CommunityController::class, 'addComment'])->name('post.comment');
    Route::post('/post/{id}/share', [CommunityController::class, 'sharePost'])->name('post.share');
});

// --- REDIRECTS ---
Route::get('/trends', function () { return redirect()->route('community.trends'); })->name('trends');
Route::get('/to', function () { return redirect()->route('community.todays-outfit'); })->name('to');

// --- PRODUCT DETAIL ---
Route::prefix('products')->name('products.')->group(function () {
    Route::get('/{id}', [ProductController::class, 'show'])->name('detail');
});

// =========================================================
//  USER ROUTES (REQURE LOGIN)
// =========================================================
Route::middleware(['auth'])->group(function () {

    // --- CART ---
    Route::get('/cart', [CartController::class, 'index'])->name('cart');
    Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
    Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/delete', [CartController::class, 'delete'])->name('cart.delete');

    // --- CHECKOUT ---
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'processCheckout'])->name('checkout.process');

    // --- ADDRESS (Checkout) ---
    Route::post('/checkout/address/add', [CheckoutController::class, 'addAddress'])->name('checkout.address.add');
    Route::put('/checkout/address/update/{id}', [CheckoutController::class, 'updateAddress'])->name('checkout.address.update');
    Route::delete('/checkout/address/delete/{id}', [CheckoutController::class, 'deleteAddress'])->name('checkout.address.delete');

    // --- ORDERS (User Side) ---
    Route::get('/my-orders', [OrderController::class, 'list'])->name('orders.list');
    Route::get('/orders/details/{order_id}', [OrderController::class, 'getOrderDetailsAjax'])->name('orders.details.ajax');
    Route::get('/payment/details', [PaymentController::class, 'showPaymentDetails'])->name('payment.details');

    // --- FAVORITES ---
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/favorite/add', [FavoriteController::class, 'store'])->name('favorite.add');
    Route::get('/favorite/hapus/{id_fav}', [FavoriteController::class, 'hapus'])->name('favorite.delete');

    // --- PROFILE ---
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::post('/upload', [ProfileController::class, 'uploadImage'])->name('upload');
        Route::get('/post/create', [ProfileController::class, 'showCreatePostForm'])->name('post.create');
        Route::post('/post', [ProfileController::class, 'storePost'])->name('post.store');
    });
    Route::get('/settings/profile', [ProfileController::class, 'showSettings'])->name('profile.settings');
    Route::post('/update-profile', [ProfileController::class, 'updateSettings'])->name('profile.update');

    // --- STYLE JOURNAL (User) ---
    Route::get('/style-journal', [StyleJournalController::class, 'index'])->name('journal.index');
    Route::get('/style-journal/create', [StyleJournalController::class, 'create'])->name('journal.create');
    Route::post('/style-journal', [StyleJournalController::class, 'store'])->name('journal.store');
    Route::get('/style-journal/{style_journal}', [StyleJournalController::class, 'show'])->name('journal.show');
    Route::get('/style-journal/{style_journal}/edit', [StyleJournalController::class, 'edit'])->name('journal.edit');
    Route::put('/style-journal/{style_journal}', [StyleJournalController::class, 'update'])->name('journal.update');
    Route::delete('/style-journal/{style_journal}', [StyleJournalController::class, 'destroy'])->name('journal.destroy');
});


// =========================================================
//  ADMIN ROUTES
// =========================================================

Route::get('/dashboard', [DashboardAdminController::class, 'index'])->name('dashboard.dashboardAdmin');

    Route::prefix('admin')->group(function () {
        
        // Orders Admin
        Route::get('/orders', [OrdersAdminController::class, 'index'])->name('admin.orders.index');
        Route::post('/orders/update-status', [OrdersAdminController::class, 'updateStatus'])->name('admin.order.updateStatus');
        Route::get('/orders/{order_id}', [OrdersAdminController::class, 'show'])->name('admin.order.detail');

        // Products Admin
        Route::get('/products', [ProductsAdminController::class, 'index'])->name('products.index'); 
        Route::get('/products/add', [ProductsAdminController::class, 'add'])->name('products.add');
        Route::post('/products', [ProductsAdminController::class, 'store'])->name('products.store');
        Route::get('/products/{id}/edit', [ProductsAdminController::class, 'edit'])->name('products.edit');
        Route::put('/products/{id}', [ProductsAdminController::class, 'update'])->name('products.update');
        Route::delete('/products/{id}', [ProductsAdminController::class, 'destroy'])->name('products.destroy');
    });

Route::get('/users-admin', [UsersAdminController::class, 'index'])->name('users-admin.usersAdmin');
Route::get('/toAdmin', [TodaysOutfitAdminController::class, 'index'])->name('toAdmin.toAdmin');

// Style Journal Admin
Route::resource('stylejournalAdmin', StyleJournalAdminController::class);