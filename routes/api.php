<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PostController;

// === AREA BEBAS (PUBLIC) ===
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');

// GET (Lihat) ditaruh di LUAR agar bisa diakses umum
Route::get('/posts', [PostController::class, 'index']);
Route::get('/posts/{post}', [PostController::class, 'show']); // <-- Ganti {id} jadi {post}
// === AREA MEMBER (PROTECTED) ===
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // Create, Update, Delete ditaruh DI DALAM (Butuh Token)
    Route::post('/posts', [PostController::class, 'store']);
    Route::put('/posts/{post}', [PostController::class, 'update']); // <-- HARUS {post}
    Route::delete('/posts/{post}', [PostController::class, 'destroy']); // <-- Ganti {id} jadi {post}
});
