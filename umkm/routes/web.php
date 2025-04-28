<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\front\NewsController; // Import NewsController

Route::get('/', function () {
    return view('dashboard');
});

Route::get('/maps', function () { return view('maps'); });

Route::get('/profile', function () { return view('profile'); });

Route::get('/news', [NewsController::class, 'index'])->name('news.index');

Route::get('/news/{id}', function ($id) {
    return view('news-detail', ['id' => $id]);
});

// Route::get('/news/{id}', [NewsController::class, 'details'])->name('news.details');


Route::get('/contact', function () { return view('contact'); });

