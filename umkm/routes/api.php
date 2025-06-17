<?php

use App\Models\UMKM;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UMKMApiController;

Route::get('/umkm', [UMKMApiController::class, 'index']);