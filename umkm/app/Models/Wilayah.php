<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wilayah extends Model
{
    protected $table = 'wilayah';
    protected $primaryKey = 'id_wilayah';
    public $incrementing = false;
    protected $fillable = ['nama_wilayah', 'deskripsi'];

    public function umkm()
    {
        return $this->hasMany(UMKM::class, 'id_wilayah');
    }
}
