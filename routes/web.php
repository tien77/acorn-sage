<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HelloController;
use App\Http\Controllers\ContactController;
use App\Livewire\ContactForm;


Route::get('/boom', function () {
    // abort(500, 'Something went wrong!');
    return throw new \RuntimeException('Test Ignition/Whoops');
});


Route::get('/', function () {
	return view('pages.home');
})->name('home');


// Contact form routes (thuần HTML)
Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');
Route::get('/my-contacts', [ContactController::class, 'myContacts'])->name('contacts.mine');

//add route alpine.test
Route::get('/alpine-test', function () {
    return view('pages.alpine-test');
})->name('alpine.test');
Route::get('/contact-alpine', function () {
    return view('livewire.contact-form-validate-alpine');
})->name('contact.alpine');

// Contact form routes (Livewire)
Route::get('/contact-livewire', ContactForm::class)->name('contact.livewire');

Route::view('/prices', 'pages.prices')->name('prices');


// JSON endpoint
Route::middleware(['log'])->group(function () {
    Route::get('/api/ping', fn () => response()->json(['ok' => true]));
});