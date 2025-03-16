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
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id('id_transaksi'); // Primary Key otomatis
            $table->foreignId('id_user')->constrained('users'); // FK ke User (yang membeli)
            $table->string('id_umkm'); // FK ke UMKM (yang menjual)
            $table->enum('status_pembayaran', ['pending', 'paid', 'failed'])->default('pending'); // Status pembayaran
            $table->integer('total'); // Total harga
            $table->date('tanggal_transaksi'); // Tanggal transaksi
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
        Schema::dropIfExists('transaksis');
    }
};
