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
        Schema::create('produk', function (Blueprint $table) {
            $table->id('id_produk'); // Primary Key otomatis
            $table->string('id_umkm'); // FK ke UMKM
            $table->string('nama_produk'); // Nama produk
            $table->text('deskripsi')->nullable(); // Deskripsi produk
            $table->integer('harga'); // Harga produk
            $table->integer('stok'); // Stok produk
            $table->string('gambar')->nullable(); // URL gambar produk
            $table->timestamps();
    
            // Definisi Foreign Key
            $table->foreign('id_umkm')->references('id_umkm')->on('umkm')->onDelete('cascade');
        });
    }
    
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produks');
    }
};
