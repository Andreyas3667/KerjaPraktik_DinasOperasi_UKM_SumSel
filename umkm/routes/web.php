<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\front\NewsController; // Import NewsController
use App\Http\Controllers\AdminController; // Import AdminController
use App\Http\Controllers\DashboardController; // Import DashboardController
use App\Http\Controllers\UMKMController; // Import UMKMController
use App\Http\Controllers\PenjualanController; // Import PenjualanController
use App\Http\Controllers\MapsController;
use App\Http\Controllers\ProdukController;

Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');


Route::prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/users', [AdminController::class, 'manageUsers'])->name('admin.users');
    Route::get('/news', [AdminController::class, 'manageNews'])->name('admin.news');
    Route::get('/umkm', [UMKMController::class, 'manage'])->name('admin.umkm.index');
    Route::post('/umkm', [UMKMController::class, 'store'])->name('admin.umkm.store');
    Route::put('/umkm/{id}', [UMKMController::class, 'update'])->name('admin.umkm.update');
    Route::delete('/umkm/{id}', [UMKMController::class, 'destroy'])->name('admin.umkm.destroy');
    Route::get('/umkm/{id}', [UMKMController::class, 'show'])->name('admin.umkm.show');
    Route::get('/umkm/search', [UMKMController::class, 'ajaxSearch'])->name('admin.umkm.search');
    Route::resource('admin-wilayah', \App\Http\Controllers\AdminWilayahController::class);
});

Route::prefix('admin/penjualan')->group(function() {
    Route::get('history', [\App\Http\Controllers\PenjualanController::class, 'history'])->name('penjualan.history');
    Route::get('laporan', [\App\Http\Controllers\PenjualanController::class, 'laporan'])->name('penjualan.laporan');
    Route::get('/', [PenjualanController::class, 'index'])->name('penjualan.index');
    Route::get('export', [PenjualanController::class, 'exportPdf'])->name('penjualan.export');
    Route::post('/admin/penjualan/verifikasi/{id}', [PenjualanController::class, 'verifikasi'])->name('penjualan.verifikasi');
    Route::delete('admin/penjualan/{id}', [PenjualanController::class, 'destroy'])->name('penjualan.destroy');
    Route::get('/{id}', [PenjualanController::class, 'show'])->name('penjualan.show');
    Route::get('/{id}/edit', [PenjualanController::class, 'edit'])->name('penjualan.edit');
    Route::put('/{id}', [PenjualanController::class, 'update'])->name('penjualan.update');
});

Route::prefix('umkm')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\UMKMUserController::class, 'dashboard'])->name('umkm.dashboard');
    Route::get('/produk', [\App\Http\Controllers\UMKMUserController::class, 'produk'])->name('umkm.produk');
    Route::get('/produk/create', [ProdukController::class, 'create'])->name('umkm.produk.create');
    Route::post('/produk', [ProdukController::class, 'store'])->name('umkm.produk.store');
    Route::get('/produk/{id}/edit', [ProdukController::class, 'edit'])->name('umkm.produk.edit');
    Route::put('/produk/{id}', [ProdukController::class, 'update'])->name('umkm.produk.update');
    Route::delete('/produk/{id}', [ProdukController::class, 'destroy'])->name('umkm.produk.destroy');
    Route::get('/laporan', [\App\Http\Controllers\UMKMUserController::class, 'laporan'])->name('umkm.laporan');
    Route::get('/laporan/export', [\App\Http\Controllers\UMKMUserController::class, 'exportPdf'])->name('umkm.laporan.export');
    Route::get('/profile', [\App\Http\Controllers\UMKMUserController::class, 'profile'])->name('umkm.profile');
    Route::post('/profile/update', [\App\Http\Controllers\UMKMUserController::class, 'updateProfile'])->name('umkm.profile.update');
});

// Dashboard customer (landing page)
Route::get('/', [DashboardController::class, 'dashboard'])->name('dashboard');

// Peta UMKM
Route::get('/maps', [MapsController::class, 'index'])->name('maps.index');

// Profil user (jika ada fitur login customer)
Route::get('/profile', function() {
    return view('profile');
})->name('profile');

// Detail UMKM (dari peta/dashboard)
Route::get('/umkm/{id}', [\App\Http\Controllers\UMKMController::class, 'show'])->name('umkm.detail');

Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

// Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/admin/umkm/manage', [UMKMController::class, 'manage'])->name('admin.umkm.manage');
Route::post('/admin/umkm', [UMKMController::class, 'store'])->name('admin.umkm.store');
Route::put('/admin/umkm/{id}', [UMKMController::class, 'update'])->name('admin.umkm.update');
Route::delete('/admin/umkm/{id}', [UMKMController::class, 'destroy'])->name('admin.umkm.destroy');
Route::get('/admin/umkm/search', [UMKMController::class, 'ajaxSearch'])->name('admin.umkm.search');