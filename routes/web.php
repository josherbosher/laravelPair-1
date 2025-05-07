<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MessageController;
use Illuminate\Support\Facades\Route;
<<<<<<< HEAD
use App\Http\Controllers\Auth\GoogleController;
=======
use App\Http\Controllers\MessagesController;
use App\Http\Controllers\ChatController;
 
use App\Http\Controllers\UserController;
>>>>>>> da43198d42179199903f271430123ae8bec1b9d2

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('guest')->group(function () {
    Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])
        ->name('google.login');
    Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])
        ->name('google.callback');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Add message routes
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{user}', [MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/{user}', [MessageController::class, 'store'])->name('messages.store');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/messages', [MessagesController::class, 'index'])->name('messages.index');
    Route::get('/users/list', [UserController::class, 'getUsers'])->name('users.list');
    Route::post('/chats', [ChatController::class, 'store'])->name('chats.store');
});

require __DIR__.'/auth.php';
