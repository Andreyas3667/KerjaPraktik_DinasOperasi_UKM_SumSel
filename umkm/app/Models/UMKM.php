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
    protected $keyType = 'int';

    protected $fillable = [
        'id_umkm',
        'nama_usaha',
        'alamat',
        'kontak',
        'id_wilayah',
        'id_user',
        'longitude',
        'latitude',
        // tambahkan field lain jika ada
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_users');
    }

    public function wilayah()
    {
        return $this->belongsTo(Wilayah::class, 'id_wilayah');
    }

    public function produk()
    {
        return $this->hasMany(\App\Models\Produk::class, 'id_umkm');
    }

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'id_umkm');
    }
}
