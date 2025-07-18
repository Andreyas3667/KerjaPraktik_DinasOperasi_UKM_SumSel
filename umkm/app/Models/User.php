<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $table = 'users'; // Nama tabel di database
    protected $primaryKey = 'id_users'; // Gunakan kolom 'id_users' sebagai primary key
    public $incrementing = true;
    protected $keyType = 'string'; // Sesuaikan dengan tipe kolom 'id_users'

    protected $fillable = [
        'nama',
        'email',
        'password',
        'role',
        'id_wilayah',
        'telepon',
        'alamat'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function wilayah()
    {
        return $this->belongsTo(\App\Models\Wilayah::class, 'id_wilayah');
    }
    public function umkm()
    {
        return $this->hasOne(\App\Models\UMKM::class, 'id_user', 'id_users'); // sesuaikan foreign key jika perlu
    }
}
