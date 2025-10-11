<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RefreshController;
use App\Http\Controllers\Api\MeController;

// Auth routes
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout']);
Route::post('refresh', RefreshController::class);


// Protected by access token (JWT short-lived)
Route::middleware(['api', 'auth.jwt'])->group(function () {
	Route::get('/me', [MeController::class, '__invoke']);   // protected
});