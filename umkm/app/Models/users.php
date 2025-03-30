<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // Pastikan role bisa diisi
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function umkm(): HasMany
    {
        return $this->hasMany(UMKM::class);
    }
}
