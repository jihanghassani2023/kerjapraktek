<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


return new class extends Migration
{
    public function up(): void
    {
        Schema::create('perbaikan', function (Blueprint $table) {
            $table->string('id', 10)->primary(); // TAMBAH BARIS INI
            $table->string('nama_device', 100);
            $table->string('kategori_device', 50)->nullable();
            $table->date('tanggal_perbaikan');
            $table->string('masalah', 200);
            $table->text('tindakan_perbaikan');
            $table->json('proses_pengerjaan')->nullable();
            $table->decimal('harga', 15, 2);
            $table->string('garansi', 50);
            $table->enum('status', ['Menunggu', 'Proses', 'Selesai'])->default('Menunggu');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('pelanggan_id')->nullable()->constrained('pelanggan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('perbaikan');
    }
};
