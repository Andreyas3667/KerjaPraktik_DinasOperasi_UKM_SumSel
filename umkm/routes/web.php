<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UMKMController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Models\umkm;


Route::get('/', function () {
    return view('home');
});

Route::get('/dashboard', function () {
    if (auth()->check()) {
        return auth()->user()->role === 'admin'
            ? redirect()->route('dashboard.admin')
            : redirect()->route('dashboard.umkm');
    }
    return redirect()->route('login');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/get-umkm', function () {
    return response()->json(App\Models\umkm::all());
});


Route::get('/umkm', [UMKMController::class, 'home']);
Route::get('/umkm/{id}', [UMKMController::class, 'show']);

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'admin'])->name('dashboard.admin');
});

Route::middleware(['auth', 'role:umkm'])->group(function () {
    Route::get('/umkm/dashboard', [DashboardController::class, 'umkm'])->name('dashboard.umkm');
});

// Route login/logout
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');


require __DIR__.'/auth.php';
