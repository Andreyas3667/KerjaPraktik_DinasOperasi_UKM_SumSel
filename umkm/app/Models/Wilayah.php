<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wilayah extends Model
{
    use HasFactory;

    protected $table = 'wilayah';
    protected $primaryKey = 'id_wilayah';
    public $incrementing = false;
    protected $fillable = ['nama_wilayah'];

    public function umkm()
    {
        return $this->hasMany(UMKM::class, 'id_wilayah');
    }
}
