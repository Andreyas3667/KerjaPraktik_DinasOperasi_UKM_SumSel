<?php

use Illuminate\Support\Facades\Route;
use App\Models\Umkm;
use App\Http\Controllers\ProdukController;

Route::post('/produk', [ProdukController::class, 'store']);
Route::get('/umkm/{id}', [ProdukController::class, 'show']);
Route::get('/produk/{id}/edit', [ProdukController::class, 'edit']);
Route::put('/produk/{id}', [ProdukController::class, 'update']);
Route::delete('/produk/{id}', [ProdukController::class, 'destroy']);

Route::get('/', function () {
    $umkms = Umkm::all();
    return view('welcome', compact('umkms'));
});
