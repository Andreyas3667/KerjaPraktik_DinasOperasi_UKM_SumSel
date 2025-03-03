<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Umkm extends Model
{
    public function produks()
    {
        return $this->hasMany(\App\Models\Produk::class);
    }
}
