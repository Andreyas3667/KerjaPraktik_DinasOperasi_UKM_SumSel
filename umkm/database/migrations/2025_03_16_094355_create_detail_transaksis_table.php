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
        Schema::create('detail_transaksi', function (Blueprint $table) {
            $table->id('id_detail'); // Primary Key otomatis
            $table->foreignId('id_transaksi')->constrained('transaksi')->onDelete('cascade'); // FK ke transaksi
            $table->foreignId('id_produk')->constrained('produk')->onDelete('cascade'); // FK ke produk
            $table->integer('jumlah'); // Jumlah produk yang dibeli
            $table->integer('harga_satuan'); // Harga satuan produk
            $table->timestamps();
        });
    }
    
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_transaksis');
    }
};
