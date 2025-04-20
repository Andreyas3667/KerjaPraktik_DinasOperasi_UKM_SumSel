<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UMKM extends Model
{
    use HasFactory;

    protected $table = 'umkm';
    protected $primaryKey = 'id_umkm';
    public $incrementing = false;
    protected $fillable = [
        'nama_usaha', 'deskripsi', 'alamat', 'kontak',
        'longitude', 'latitude', 'id_wilayah', 'id_user'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function wilayah()
    {
        return $this->belongsTo(Wilayah::class, 'id_wilayah');
    }

    public function produk()
    {
        return $this->hasMany(Produk::class, 'id_umkm');
    }

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'id_umkm');
    }
}
