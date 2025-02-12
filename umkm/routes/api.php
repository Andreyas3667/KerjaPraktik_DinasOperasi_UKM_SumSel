use App\Http\Controllers\UMKMController;

Route::get('/umkm', [UMKMController::class, 'index']);
Route::post('/umkm', [UMKMController::class, 'store']);
Route::get('/umkm/{id}', [UMKMController::class, 'show']);
