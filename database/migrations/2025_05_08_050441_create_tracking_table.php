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
        // Kita tidak perlu membuat tabel khusus, karena menggunakan tabel perbaikan yang sudah ada
        // Namun kita bisa menambahkan kolom tambahan jika diperlukan
        Schema::table('perbaikan', function (Blueprint $table) {
            // Pastikan kode_perbaikan sudah memiliki index untuk pencarian yang lebih cepat
            $table->index('kode_perbaikan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('perbaikan', function (Blueprint $table) {
            $table->dropIndex(['kode_perbaikan']);
        });
    }
};