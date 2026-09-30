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
        Schema::create('detail_pemeriksaan', function (Blueprint $table) {

            $table->id('id_detail');

            // Relasi ke tabel pemeriksaan
            $table->unsignedBigInteger('id_pemeriksaan');

            // Relasi ke master sarana
            $table->unsignedBigInteger('id_sarana');

            // Urutan gerbong pada rangkaian saat pemeriksaan
            $table->unsignedTinyInteger('urutan');

            // Checklist perangkat
            $table->enum('pids_luar', ['B', 'R', 'T']);
            $table->enum('pids_dalam', ['B', 'R', 'T']);
            $table->enum('cctv', ['B', 'R', 'T']);
            $table->enum('wifi', ['B', 'R', 'T']);
            $table->enum('backup', ['B', 'R', 'T']);
            $table->enum('sisi_a', ['B', 'R', 'T']);
            $table->enum('sisi_e', ['B', 'R', 'T']);
            $table->enum('td_kecil', ['B', 'R', 'T']);
            $table->enum('td_besar', ['B', 'R', 'T']);

            // Catatan khusus untuk gerbong ini
            $table->text('keterangan')->nullable();

            $table->timestamps();

            // Foreign Key
            $table->foreign('id_pemeriksaan')
                ->references('id_pemeriksaan')
                ->on('pemeriksaan')
                ->onDelete('cascade');

            $table->foreign('id_sarana')
                ->references('id_sarana')
                ->on('sarana')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_pemeriksaan');
    }
};