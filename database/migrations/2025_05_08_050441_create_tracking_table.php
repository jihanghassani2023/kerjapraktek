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
        // Periksa apakah tabel perbaikan sudah ada
        if (Schema::hasTable('perbaikan')) {
            // Periksa apakah index sudah ada
            $sm = Schema::getConnection()->getDoctrineSchemaManager();
            $indexes = $sm->listTableIndexes('perbaikan');
            if (!array_key_exists('perbaikan_kode_perbaikan_index', $indexes)) {
                Schema::table('perbaikan', function (Blueprint $table) {
                    $table->index('kode_perbaikan');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('perbaikan')) {
            Schema::table('perbaikan', function (Blueprint $table) {
                $table->dropIndex(['kode_perbaikan']);
            });
        }
    }
};
