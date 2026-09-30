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
        Schema::table('sarana', function (Blueprint $table) {
            $table->dropUnique('sarana_nomor_sarana_unique');
            $table->unique(['id_kereta', 'nomor_sarana']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sarana', function (Blueprint $table) {
            $table->dropUnique(['id_kereta', 'nomor_sarana']);
            $table->unique('nomor_sarana');
        });
    }
};
