<?php

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\front\NewsController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UMKMController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\MapsController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UMKMUserController;
use App\Http\Controllers\Api\UMKMApiController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\Auth\RegisteredUserController;

// ===================
// Dashboard Umum/User
// ===================
Route::get('/', [DashboardController::class, 'userDashboard'])->name('dashboard');
Route::get('/dashboard', [DashboardController::class, 'userDashboard'])->name('dashboard');

// ===================
// Admin Area
// ===================
Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('admin.dashboard');
    Route::get('/users', [AdminController::class, 'manageUsers'])->name('admin.users');
    Route::get('/news', [AdminController::class, 'manageNews'])->name('admin.news');
    Route::get('/umkm', [UMKMController::class, 'manage'])->name('admin.umkm.index');
    Route::post('/umkm', [UMKMController::class, 'store'])->name('admin.umkm.store');
    Route::put('/umkm/{id}', [UMKMController::class, 'update'])->name('admin.umkm.update');
    Route::delete('/umkm/{id}', [UMKMController::class, 'destroy'])->name('admin.umkm.destroy');
    Route::get('/umkm/{id}', [UMKMController::class, 'show'])->name('admin.umkm.show');
    Route::get('/umkm/search', [UMKMController::class, 'ajaxSearch'])->name('admin.umkm.search');
    Route::resource('admin-wilayah', \App\Http\Controllers\AdminWilayahController::class);
    // Penjualan admin
    Route::prefix('penjualan')->group(function() {
        Route::get('history', [PenjualanController::class, 'history'])->name('penjualan.history');
        Route::get('laporan', [PenjualanController::class, 'laporan'])->name('penjualan.laporan');
        Route::get('/', [PenjualanController::class, 'index'])->name('penjualan.index');
        Route::get('export', [PenjualanController::class, 'exportPdf'])->name('penjualan.export');
        Route::post('verifikasi/{id}', [PenjualanController::class, 'verifikasi'])->name('penjualan.verifikasi');
        Route::delete('{id}', [PenjualanController::class, 'destroy'])->name('penjualan.destroy');
        Route::get('{id}', [PenjualanController::class, 'show'])->name('penjualan.show');
        Route::get('{id}/edit', [PenjualanController::class, 'edit'])->name('penjualan.edit');
        Route::put('{id}', [PenjualanController::class, 'update'])->name('penjualan.update');
        Route::post('/admin/penjualan/{id}/batal', [\App\Http\Controllers\PenjualanController::class, 'batal'])->name('penjualan.batal');
    });
});

// ===================
// UMKM Area
// ===================
Route::prefix('umkm')->middleware(['auth', 'role:umkm'])->group(function () {
    Route::get('/dashboard', [UMKMUserController::class, 'dashboard'])->name('umkm.dashboard');
    Route::get('/produk', [UMKMUserController::class, 'produk'])->name('umkm.produk');
    Route::get('/produk/create', [ProdukController::class, 'create'])->name('umkm.produk.create');
    Route::post('/produk', [ProdukController::class, 'store'])->name('umkm.produk.store');
    Route::get('/produk/{id}/edit', [ProdukController::class, 'edit'])->name('umkm.produk.edit');
    Route::put('/produk/{id}', [ProdukController::class, 'update'])->name('umkm.produk.update');
    Route::delete('/produk/{id}', [ProdukController::class, 'destroy'])->name('umkm.produk.destroy');
    Route::get('/laporan', [UMKMUserController::class, 'laporan'])->name('umkm.laporan');
    Route::get('/laporan/export', [UMKMUserController::class, 'exportPdf'])->name('umkm.laporan.export');
    Route::get('/profile', [UMKMUserController::class, 'profile'])->name('umkm.profile');
    Route::post('/profile/update', [UMKMUserController::class, 'updateProfile'])->name('umkm.profile.update');
});

// ===================
// Fitur Umum
// ===================
Route::get('/maps', [MapsController::class, 'index'])->name('maps.index');
Route::get('/profile', function() {
    return view('profile');
})->name('profile');
Route::get('/umkm/{id}', [UMKMController::class, 'detail'])->name('umkm.detail');
Route::get('/umkm', [UMKMApiController::class, 'index']);

// ===================
// Auth & Profile
// ===================
Auth::routes();
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ===================
// Email Verification
// ===================
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/home');
})->middleware(['auth', 'signed'])->name('verification.verify');
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// ===================
// Socialite (Login Sosial Media)
// ===================
Route::get('login/google', [SocialiteController::class, 'redirectToGoogle'])->name('login.google');
Route::get('login/google/callback', [SocialiteController::class, 'handleGoogleCallback']);
Route::get('login/facebook', [SocialiteController::class, 'redirectToFacebook'])->name('login.facebook');
Route::get('login/facebook/callback', [SocialiteController::class, 'handleFacebookCallback']);
Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
Route::post('register', [RegisteredUserController::class, 'store']);
Route::post('/umkm/{id}/transaksi', [UMKMController::class, 'transaksi'])->middleware('auth')->name('umkm.transaksi');
Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
