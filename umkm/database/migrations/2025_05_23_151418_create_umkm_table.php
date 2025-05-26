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
        Schema::create('umkm', function (Blueprint $table) {
            $table->bigIncrements('id_umkm')->primary();
            $table->string('nama_usaha');
            $table->text('deskripsi')->nullable();
            $table->string('alamat');
            $table->string('kontak');
            $table->decimal('longitude', 10, 7)->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->unsignedBigInteger('id_wilayah');
            $table->unsignedBigInteger('id_user');
            $table->foreign('id_wilayah')->references('id_wilayah')->on('wilayah')->onDelete('restrict');
            $table->foreign('id_user')->references('id_users')->on('users')->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('umkm');
    }
};
