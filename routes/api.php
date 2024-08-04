<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::post('/users', [UserController::class, 'store']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']); // Optional, if you want logout functionality
Route::delete('/users/{id}', [UserController::class, 'destroy']);

Route::middleware('jwt.auth')->group(function () {
    Route::get('/posts', [PostController::class, 'index']);
    Route::post('/posts', [PostController::class, 'store']);
    Route::get('/users/{id}/posts', [PostController::class, 'getUserPosts']);

});
