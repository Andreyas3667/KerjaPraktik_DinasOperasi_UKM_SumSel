use App\Http\Controllers\UMKMcontroller;
use App\Http\Controllers\Produkcontroller;

Route::get('/umkms',[UMKMcontroller::class,'index']);
Route::post('/umkms',[UMKMcontroller::class,'store']);
Route::get('/umkms/{id}',[UMKMcontroller::class,'show']);

Route::post('/produk',[Produkcontroller::class,'store']);
Route::put('/produk/{id}',[Produkcontroller::class,'update']);
Route::delete('/produk/{id}',[Produkcontroller::class,'destroy']);