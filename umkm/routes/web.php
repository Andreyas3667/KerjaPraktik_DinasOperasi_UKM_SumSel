<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('dashboard');
});

Route::get('/maps', function () { return view('maps'); });

Route::get('/profile', function () { return view('profile'); });

Route::get('/news', function () { return view('news'); });

Route::get('/contact', function () { return view('contact'); });

