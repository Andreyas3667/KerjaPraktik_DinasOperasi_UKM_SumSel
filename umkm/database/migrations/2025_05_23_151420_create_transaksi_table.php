<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->bigIncrements('id_transaksi');
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_umkm')->nullable();
            $table->string('status_pembayaran')->default('pending'); // pending, selesai, dibatalkan
            $table->integer('total');
            $table->date('tanggal_transaksi');
            $table->timestamps();

            $table->foreign('id_user')->references('id_users')->on('users')->onDelete('restrict');
            $table->foreign('id_umkm')->references('id_umkm')->on('umkm')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};
