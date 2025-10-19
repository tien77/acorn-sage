<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HelloController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\BlogController;
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