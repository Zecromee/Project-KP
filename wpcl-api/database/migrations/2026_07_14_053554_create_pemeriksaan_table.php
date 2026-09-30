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
        Schema::create('pemeriksaan', function (Blueprint $table) {

            // Primary Key
            $table->id('id_pemeriksaan');

            // Data Petugas
            $table->string('nama_petugas', 100);
            $table->string('nipp', 20);

            // Kereta yang diperiksa
            $table->unsignedBigInteger('id_kereta');

            // Tanggal pemeriksaan
            $table->date('tanggal');

            // Business Area
            $table->string('business_area', 10)->nullable();

            // Nomor Referensi
            $table->string('no_ref', 30)->nullable();

            // Hasil pemeriksaan Locotrack
            $table->enum('locotrack', ['B', 'R', 'T']);

            // Catatan umum
            $table->text('catatan')->nullable();

            $table->timestamps();

            // Foreign Key
            $table->foreign('id_kereta')
                ->references('id_kereta')
                ->on('kereta')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemeriksaan');
    }
};