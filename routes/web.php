<?php

use App\Http\Controllers\Post\PostController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/post-add', [PostController::class, 'store']);