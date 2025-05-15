<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WilayahSeeder extends Seeder
{
    public function run()
    {
        DB::table('wilayah')->insert([
            ['id_wilayah' => 1, 'nama_wilayah' => 'Pagaralam'],
            ['id_wilayah' => 2, 'nama_wilayah' => 'Lahat'],
            ['id_wilayah' => 3, 'nama_wilayah' => 'Muara Enim'],
            ['id_wilayah' => 4, 'nama_wilayah' => 'OKU Selatan'],
            ['id_wilayah' => 5, 'nama_wilayah' => 'Empat Lawang'],
        ]);
    }
}
