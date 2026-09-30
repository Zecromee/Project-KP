<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pemeriksaan', function (Blueprint $table) {
            $table->string('loco_id', 50)->nullable()->after('locotrack');
            $table->string('nomor_sarana_loco', 50)->nullable()->after('loco_id');
            $table->string('no_dokumen', 50)->nullable()->after('nomor_sarana_loco');
            $table->string('versi_dokumen', 20)->nullable()->after('no_dokumen');
        });
    }

    public function down(): void
    {
        Schema::table('pemeriksaan', function (Blueprint $table) {
            $table->dropColumn(['loco_id', 'nomor_sarana_loco', 'no_dokumen', 'versi_dokumen']);
        });
    }
};
