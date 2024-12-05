<?php

use Illuminate\Support\Facades\Route;


Route::get('/', function(){
    return view('spa');
});

Route::view('/{any}', 'spa')->where('any', '.*');
