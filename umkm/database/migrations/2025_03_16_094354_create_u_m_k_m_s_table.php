<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('umkm', function (Blueprint $table) {
            $table->string('id_umkm')->primary(); // ID UMKM sebagai PK
            $table->string('nama_usaha'); // Nama UMKM
            $table->text('deskripsi')->nullable(); // Deskripsi usaha
            $table->string('alamat'); // Alamat usaha
            $table->string('kontak'); // Nomor kontak UMKM
            $table->decimal('longitude', 10, 7); // Longitude lokasi UMKM
            $table->decimal('latitude', 10, 7); // Latitude lokasi UMKM
            $table->string('id_wilayah'); // Foreign Key ke Wilayah
            $table->foreignId('id_user')->constrained('users'); // FK ke Users
            $table->timestamps();
    
            // Definisi Foreign Key
            $table->foreign('id_wilayah')->references('id_wilayah')->on('wilayah')->onDelete('cascade');
        });
    }
    
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('u_m_k_m_s');
    }
};
