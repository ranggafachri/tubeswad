<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\CategoryController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\CustomerMiddleware;

// Landing Page
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('products.catalog');
    }
    return view('welcome');
})->name('welcome');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
    Route::get('register', [App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [App\Http\Controllers\Auth\RegisterController::class, 'register']);

    // Password Reset Routes
    Route::get('password/reset', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('password/email', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('password/reset/{token}', [App\Http\Controllers\Auth\ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('password/reset', [App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])->name('password.update');
});

Route::post('logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

// Routes that require authentication
Route::middleware(['auth'])->group(function () {
    // Profile Routes
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('profile.show');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::post('/update', [ProfileController::class, 'update'])->name('profile.update');
    });

    // Customer Routes
    Route::middleware(['auth', \App\Http\Middleware\CustomerMiddleware::class])->group(function () {
        // Cart Routes
        Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
        Route::post('/cart/add', [CartController::class, 'addItem'])->name('cart.add-item');
        Route::put('/cart/items/{cartItem}', [CartController::class, 'updateItem'])->name('cart.update-item');
        Route::delete('/cart/items/{cartItem}', [CartController::class, 'removeItem'])->name('cart.remove-item');
        Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');

        // Catalog Route (for customers only)
        Route::get('/catalog', [ProductController::class, 'catalog'])->name('products.catalog');

        // Customer Transaction Routes
        Route::get('/my-transactions', [TransactionController::class, 'customerTransactions'])->name('customer.transactions');
        Route::get('/my-transactions/{transaction}', [TransactionController::class, 'customerTransactionDetail'])->name('customer.transactions.show');
    });
});

// Admin Routes
Route::middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])->group(function () {
    // Admin Dashboard
    Route::get('/admin', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // Category Management
    Route::resource('categories', CategoryController::class)->except(['show']);

    // Admin Transaction Management
    Route::get('/admin/transactions', [TransactionController::class, 'index'])->name('admin.transactions.index');
    Route::get('/admin/transactions/{transaction}', [TransactionController::class, 'show'])->name('admin.transactions.show');
    Route::get('/admin/transactions/{transaction}/edit', [TransactionController::class, 'edit'])->name('admin.transactions.edit');
    Route::put('/admin/transactions/{transaction}', [TransactionController::class, 'update'])->name('admin.transactions.update');
    Route::delete('/admin/transactions/{transaction}', [TransactionController::class, 'destroy'])->name('admin.transactions.destroy');

    // Customer Management
    Route::resource('customers', CustomerController::class)->parameters([
        'customers' => 'customer'
    ])->except(['create', 'store', 'edit', 'update']);

    // Product Management
    Route::resource('products', ProductController::class)->except(['show']);
    Route::get('/products/search-api', [ProductController::class, 'searchApi'])->name('products.search-api');
    Route::post('/products/import-api', [ProductController::class, 'importFromApi'])->name('products.import-api');
});

Route::get('/products/fetch-data', [ProductController::class, 'fetchProductData'])->name('products.fetch-data');