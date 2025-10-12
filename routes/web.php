<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HelloController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GalleryController;
use App\Livewire\ContactForm;


Route::get('/boom', function () {
    // abort(500, 'Something went wrong!');
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
