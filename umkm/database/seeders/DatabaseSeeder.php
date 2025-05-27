<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Hapus semua data users tanpa mengganggu foreign key
        DB::table('users')->delete();
        DB::statement('ALTER TABLE users AUTO_INCREMENT = 1');

        // // Membuat 10 user random
        // User::factory(10)->create();

        // Membuat user admin
        User::factory()->create([
            'nama' => 'admin',
            'email' => 'admin@tes.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        

    }
}
