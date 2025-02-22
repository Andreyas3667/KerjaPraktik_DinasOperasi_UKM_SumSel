<?php

use Illuminate\Support\Facades\Route;
use App\Models\Umkm;
use App\Http\Controllers\ProdukController;

Route::post('/produk', [ProdukController::class, 'store']);
Route::get('/', function () {
    $umkms = Umkm::all();
    return view('welcome', compact('umkms'));
});
