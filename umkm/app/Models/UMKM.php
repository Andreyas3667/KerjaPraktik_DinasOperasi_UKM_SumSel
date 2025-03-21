<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class umkm extends Model
{
    use HasFactory;
    
    protected $table = 'umkm';
    protected $primaryKey = 'id_umkm';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_umkm', 'nama_usaha', 'deskripsi', 'alamat', 
        'kontak', 'longitude', 'latitude', 'id_wilayah', 'id_user'
    ];
}
