<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksi';
    protected $primaryKey = 'id_transaksi';
    protected $fillable = [
        'id_user', 'id_umkm', 'status_pembayaran', 'total', 'tanggal_transaksi'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function umkm()
    {
        return $this->belongsTo(UMKM::class, 'id_umkm');
    }

    public function detail()
    {
        return $this->hasMany(DetailTransaksi::class, 'id_transaksi');
    }
}
