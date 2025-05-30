<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $table = 'produk';
    protected $primaryKey = 'id_produk';
    protected $fillable = ['id_umkm', 'nama_produk', 'deskripsi', 'stok', 'harga', 'gambar'];

    public function umkm()
    {
        return $this->belongsTo(Umkm::class);
    }
}
