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
        Schema::table('pemeriksaan', function (Blueprint $table) {
            $table->unsignedBigInteger('id_pejabat')->nullable()->after('id_kereta');
            $table->string('pejabat_nama', 100)->nullable()->after('nipp');
            $table->string('pejabat_nipp', 30)->nullable()->after('pejabat_nama');
            $table->string('pejabat_jabatan', 100)->nullable()->after('pejabat_nipp');

            $table->foreign('id_pejabat')
                ->references('id_pejabat')
                ->on('pejabat')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemeriksaan', function (Blueprint $table) {
            $table->dropForeign(['id_pejabat']);
            $table->dropColumn(['id_pejabat', 'pejabat_nama', 'pejabat_nipp', 'pejabat_jabatan']);
        });
    }
};
