<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Post\PostController;
use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');
Route::middleware('guest')->group(function(){
    Route::view('/login', 'auth.login')->name('login.view');
    Route::view('/register', 'auth.register')->name('register.view');
});

Route::post('/register', [AuthController::class, 'register'])->name('register.store');
Route::post('/login', [AuthController::class, 'login'])->name('login');


Route::middleware('auth')->group(function(){
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::prefix('profile')->name('profile.')->group(function(){
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/edit/{user}', [UserController::class, 'create'])->name('create');
        Route::put('/update/{user}', [UserController::class, 'update'])->name('update');
    });

    Route::resource('post', PostController::class);

    Route::post('/search', [UserController::class, 'search'])->name('search');
});