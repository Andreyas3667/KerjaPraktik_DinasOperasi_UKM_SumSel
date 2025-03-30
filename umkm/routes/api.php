<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/umkm', [App\Http\Controllers\Api\UMKMController::class, 'index']);
