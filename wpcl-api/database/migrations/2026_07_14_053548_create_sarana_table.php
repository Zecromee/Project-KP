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
    Schema::create('sarana', function (Blueprint $table) {

        $table->id('id_sarana');

        $table->string('kode_sarana',10);

        $table->string('nomor_sarana',20)->unique();

        $table->string('seri_sarana',50)->nullable();

        $table->string('depo_induk',20)->nullable();

        $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sarana');
    }
};
