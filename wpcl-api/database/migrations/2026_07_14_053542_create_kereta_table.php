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
        Schema::create('kereta', function (Blueprint $table) {
            $table->id('id_kereta');
            $table->string('no_ka',10);
            $table->string('nama_ka',100);
            $table->string('relasi',100)->nullable();
            $table->time('jam_berangkat')->nullable();
            $table->time('jam_datang')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kereta');
    }
};
