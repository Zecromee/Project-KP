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
            $table->unsignedBigInteger('id_kereta')->nullable()->after('id_sarana');

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
        Schema::table('sarana', function (Blueprint $table) {
            $table->dropForeign(['id_kereta']);
            $table->dropColumn('id_kereta');
        });
    }
};
