<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UMKMApiController;

Route::get('/umkm', [UMKMApiController::class, 'index']);