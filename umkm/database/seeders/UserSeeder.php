<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('users')->insert([
            'nama' => 'Admin Utama',
            'email' => 'admin@umkmkopi.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);
    }
    
}
