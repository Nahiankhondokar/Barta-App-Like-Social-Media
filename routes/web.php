<?php

use App\Http\Controllers\API\Post\PostInteractionController;
use Illuminate\Support\Facades\Route;


Route::get('/', function(){
    return view('spa');
});

Route::prefix('post')->group(function(){
    Route::post('/liked', [PostInteractionController::class, "liked"]);
});

// Route::view('/{any}', 'spa')->where('any', '.*');
