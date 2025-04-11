<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('dashboard');
});

Route::get('/maps', function () { return view('maps'); });

Route::get('/profile', function () { return view('profile'); });
