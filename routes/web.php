<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Post\PostController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');
Route::view('/login', 'auth.login')->name('login.view');
Route::view('/register', 'auth.register');

Route::post('/register', [AuthController::class, 'register'])->name('register.store');
Route::post('/login', [AuthController::class, 'login'])->name('login');


Route::middleware('auth')->group(function(){
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
});