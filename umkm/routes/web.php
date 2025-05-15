<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\front\NewsController; // Import NewsController
use App\Http\Controllers\AdminController; // Import AdminController
use App\Http\Controllers\DashboardController; // Import DashboardController
use App\Http\Controllers\UMKMController; // Import UMKMController

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Route::get('/maps', function () { return view('maps'); });

// Route::get('/profile', function () { return view('profile'); });

// Route::get('/news', [NewsController::class, 'index'])->name('news.index');

// Route::get('/news/{id}', function ($id) {
//     return view('news-detail', ['id' => $id]);
// });

Route::get('/news/{id}', [NewsController::class, 'details'])->name('news.details');

// Route::get('/contact', function () { return view('contact'); });

Route::prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/users', [AdminController::class, 'manageUsers'])->name('admin.users');
    Route::get('/news', [AdminController::class, 'manageNews'])->name('admin.news');
    Route::get('/umkm', [UMKMController::class, 'manage'])->name('admin.umkm.manage');
    Route::post('/umkm', [UMKMController::class, 'store'])->name('admin.umkm.store');
    Route::put('/umkm/{id}', [UMKMController::class, 'update'])->name('admin.umkm.update');
    Route::delete('/umkm/{id}', [UMKMController::class, 'destroy'])->name('admin.umkm.destroy');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
