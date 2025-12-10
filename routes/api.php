<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// jalur Public tanpa Kunci
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');

// jalur harus login dan register
// Middleware 'auth:sanctum' ini adalah satpam untuk pengecekan

Route::middleware('auth:sanctum')->group(function () {
    // fitur logout
    Route::post('/logout', [AuthController::class, 'logout']);
});
