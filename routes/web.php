<?php

use App\Http\Controllers\API\Post\PostInteractionController;
use Illuminate\Support\Facades\Route;


Route::get('/', function(){
    return view('spa');
});

Route::view('/{any}', 'spa')->where('any', '.*');
