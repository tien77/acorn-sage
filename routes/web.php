<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HelloController;
use App\Http\Controllers\ContactController;
use App\Livewire\ContactForm;

Route::get('/', function () {

	return view('pages.home');
})->name('home');

Route::middleware(['log'])->group(function () {
    Route::get('/hello', [HelloController::class, 'index'])->name('hello');
});

Route::get('/blog/spa', function () {
    return view('pages.blog-spa');
})->name('blog.spa')->middleware('log');


// Contact form routes (thuần HTML)
Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');
Route::get('/my-contacts', [ContactController::class, 'myContacts'])->name('contacts.mine');

// Contact form routes (Livewire)
Route::get('/contact-livewire', ContactForm::class)->name('contact.livewire');



// JSON endpoint
Route::middleware('throttle:60,1')->group(function () {
    Route::get('/api/ping', fn () => response()->json(['ok' => true]));
});
