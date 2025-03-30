<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class umkm extends Model
{
    use HasFactory;

    protected $table = 'umkm'; // Sesuaikan dengan nama tabel di database

    protected $fillable = [
        'name',
        'description',
        'location',
        'land_size',
        'products',
        'whatsapp',
        'user_id' // Relasi ke tabel users
    ];

    // Relasi ke user (pemilik UMKM)
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
