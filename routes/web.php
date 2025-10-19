<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HelloController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Livewire\ContactForm;


Route::get('/boom', function () {
    // abort(500, 'Something went wrong!');
    logger()->channel('deleted-posts')->info('Boom!');
    return throw new \RuntimeException('Test Ignition/Whoops');
});


Route::get('/', function () {
	return view('pages.home');
})->name('home');

// Authentication routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes (cần đăng nhập)
Route::middleware(['auth.wp'])->group(function () {
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
});


// Contact form routes (thuần HTML)
Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');

// Protected contact routes
Route::middleware(['auth.wp'])->group(function () {
    Route::get('/my-contacts', [ContactController::class, 'myContacts'])->name('contacts.mine');
});

//add route alpine.test
Route::get('/alpine-test', function () {
    return view('pages.alpine-test');
})->name('alpine.test');
Route::get('/contact-alpine', function () {
    return view('livewire.contact-form-validate-alpine');
})->name('contact.alpine');

// Contact form routes (Livewire)
Route::get('/contact-livewire', ContactForm::class)->name('contact.livewire');

// Gallery routes
Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery.index');
Route::get('/gallery/{id}', [GalleryController::class, 'show'])->name('gallery.show');
Route::get('/api/gallery/category', [GalleryController::class, 'getByCategory'])->name('gallery.category');

Route::view('/prices', 'pages.prices')->name('prices');

// Blog routes
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

// Blog API routes (để support AJAX/Livewire)
Route::get('/api/blog', [BlogController::class, 'apiIndex'])->name('api.blog.index');
Route::get('/api/blog/categories', [BlogController::class, 'getCategories'])->name('api.blog.categories');

// Product routes
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');
Route::get('/category/{slug}', [ProductController::class, 'category'])->name('products.category');

// Product API routes
Route::get('/api/products', [ProductController::class, 'apiIndex'])->name('api.products.index');

// Cart routes
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
Route::post('/cart/quick-add', [CartController::class, 'quickAdd'])->name('cart.quick-add');

// Cart API routes
Route::get('/api/cart/info', [CartController::class, 'getCartInfo'])->name('api.cart.info');
Route::post('/api/cart/merge', [CartController::class, 'mergeCart'])->name('api.cart.merge');

// Order routes
Route::get('/checkout', [OrderController::class, 'checkout'])->name('orders.checkout');
Route::post('/checkout', [OrderController::class, 'store'])->name('orders.store');
Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
Route::get('/orders/{order}/payment-info', [OrderController::class, 'paymentInfo'])->name('orders.payment-info');

// Protected order routes
Route::middleware(['auth.wp'])->group(function () {
    Route::get('/my-orders', [OrderController::class, 'myOrders'])->name('orders.mine');
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
});

// Order API routes
Route::post('/api/orders/calculate-shipping', [OrderController::class, 'calculateShipping'])->name('api.orders.shipping');