<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // Tambahkan ini

class UmkmSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('umkms')->insert([
            [
                'nama' => 'Kopi Pagaralam',
                'deskripsi' => 'UMKM kopi robusta terbaik di Pagaralam',
                'lokasi' => 'Pagaralam, Sumatera Selatan',
                'latitude' => -4.048,
                'longitude' => 103.246,
                'luas_tanah' => 5.5,
                'whatsapp' => '628123456789',
            ],
        ]);
    }
}
