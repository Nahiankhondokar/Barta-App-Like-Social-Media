<?php

use App\Http\Controllers\API\v1\Auth\AuthController;
use App\Http\Controllers\API\v1\Post\PostController;
use App\Http\Controllers\API\v1\User\UserController;
use App\Http\Controllers\DashboardController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


Route::middleware('guest')->group(function(){
    Route::view('/login', 'auth.login')->name('login.view');
    Route::view('/register', 'auth.register')->name('register.view');
});

Route::post('/register', [AuthController::class, 'register'])->name('register.store');
Route::post('/login', [AuthController::class, 'login'])->name('login');


# Route::middleware('auth:sanctum')->group(function(){
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/me', [AuthController::class, 'me'])->name('me');

    Route::resource('post', PostController::class);

    Route::prefix('profile')->name('profile.')->group(function(){
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/edit/{user}', [UserController::class, 'edit'])->name('edit');
        Route::post('/update/{user}', [UserController::class, 'update'])->name('update');
    });

    Route::post('/post-search', [UserController::class, 'search'])->name('search');
# });

// CICD TESTING