<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UMKMController;
use App\Http\Controllers\TransaksiController;

Route::get('/',[UMKMController::class, 'showMap']);

Route::get('/transaksi/{id}', [TransaksiController::class, 'show']);
