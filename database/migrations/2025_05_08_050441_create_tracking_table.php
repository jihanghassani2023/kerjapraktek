<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

        if (Schema::hasTable('perbaikan')) {
            $sm = Schema::getConnection()->getDoctrineSchemaManager();
            $indexes = $sm->listTableIndexes('perbaikan');
            if (!array_key_exists('perbaikan_kode_perbaikan_index', $indexes)) {
                Schema::table('perbaikan', function (Blueprint $table) {
                    $table->index('kode_perbaikan');
                });
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('perbaikan')) {
            Schema::table('perbaikan', function (Blueprint $table) {
                $table->dropIndex(['kode_perbaikan']);
            });
        }
    }
};
