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
            $table->string('id_umkm')->primary();
            $table->string('nama_usaha');
            $table->text('deskripsi')->nullable();
            $table->string('alamat');
            $table->string('kontak');
            $table->decimal('longitude', 10, 7);
            $table->decimal('latitude', 10, 7);
            $table->string('id_wilayah');
            $table->string('id_user');
            $table->timestamps();

            $table->foreign('id_wilayah')->references('id_wilayah')->on('wilayah')->onDelete('cascade');
            $table->foreign('id_user')->references('id_users')->on('users')->onDelete('cascade');
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
