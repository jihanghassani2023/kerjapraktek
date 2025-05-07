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
        Schema::create('perbaikan', function (Blueprint $table) {
            $table->id();
            $table->string('kode_perbaikan', 20)->unique();
            $table->string('nama_barang');
            $table->date('tanggal_perbaikan');
            $table->string('masalah');
            $table->string('nama_pelanggan');
            $table->string('nomor_telp');
            $table->string('email')->nullable();
            $table->decimal('harga', 15, 2)->nullable();
            $table->string('garansi')->nullable();
            $table->enum('status', ['Menunggu', 'Proses', 'Selesai'])->default('Menunggu');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perbaikan');
    }
};