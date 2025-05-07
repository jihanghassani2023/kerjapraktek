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
        Schema::create('karyawan', function (Blueprint $table) {
            $table->id();
            $table->string('id_karyawan', 20)->unique();
            $table->string('nama_karyawan');
            $table->string('ttl'); // Tanggal dan tempat lahir
            $table->text('alamat');
            $table->string('jabatan'); // Kepala Teknisi, Teknisi, Admin
            $table->string('status')->default('Kontrak'); // Kontrak, Tetap, dll
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karyawan');
    }
};
