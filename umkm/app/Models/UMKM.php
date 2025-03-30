<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UMKM extends Model
{
    use HasFactory;

    protected $table = 'umkm';
    protected $primaryKey = 'id_umkm'; // Jika primary key bukan 'id', sesuaikan
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_umkm', 'nama_usaha', 'deskripsi', 'alamat',
        'kontak', 'longitude', 'latitude', 'id_wilayah', 'id_user'
    ];
}
