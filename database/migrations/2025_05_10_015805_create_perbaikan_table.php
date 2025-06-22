<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('perbaikan', function (Blueprint $table) {
            $table->string('id', 11)->primary();
            $table->date('tanggal_perbaikan');
            $table->enum('status', ['Menunggu', 'Proses', 'Selesai'])->default('Menunggu');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->unsignedInteger('pelanggan_id');
            $table->foreign('pelanggan_id')->references('id')->on('pelanggan')->onDelete('cascade');

            $table->string('nama_device', 100);
            $table->string('kategori_device', 50)->nullable();
            $table->string('masalah', 200);
            $table->text('tindakan_perbaikan');
            $table->decimal('harga', 15, 2);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('perbaikan');
    }
};
