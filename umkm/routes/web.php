<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UMKMController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\TransaksiController;

Route::get('/umkm', [UMKMController::class, 'index']);
Route::get('/umkm/{id}', [UMKMController::class, 'show']);
Route::post('/umkm', [UMKMController::class, 'store']);


Route::get('/', function () {
    return view('welcome');
});
