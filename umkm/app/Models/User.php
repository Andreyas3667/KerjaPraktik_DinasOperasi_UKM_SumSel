<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users'; // Nama tabel di database
    protected $primaryKey = 'id_users'; // Gunakan kolom 'id_users' sebagai primary key
    public $incrementing = false; // Jika kolomnya string
    protected $keyType = 'string'; // Sesuaikan dengan tipe kolom 'id_users'

    protected $fillable = [
        'id_users',
        'nama',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
