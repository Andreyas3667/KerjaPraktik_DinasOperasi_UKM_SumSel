<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $table = 'produk';
    protected $primaryKey = 'id_produk';
    protected $fillable = [
        'id_umkm', 'nama_produk', 'deskripsi', 'harga', 'stok', 'gambar'
    ];

    public function umkm()
    {
        return $this->belongsTo(UMKM::class, 'id_umkm');
    }

    public function detailTransaksi()
    {
        return $this->hasMany(DetailTransaksi::class, 'id_produk');
    }
}
