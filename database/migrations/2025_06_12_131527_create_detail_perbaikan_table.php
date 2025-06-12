<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_perbaikan', function (Blueprint $table) {
            $table->id();
            $table->string('perbaikan_id', 10);
            $table->string('nama_device', 100);
            $table->string('kategori_device', 50)->nullable();
            $table->string('masalah', 200);
            $table->text('tindakan_perbaikan');
            $table->json('proses_pengerjaan')->nullable();
            $table->decimal('harga', 15, 2);
            $table->string('garansi', 50);
            $table->timestamps();

            $table->foreign('perbaikan_id')->references('id')->on('perbaikan')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_perbaikan');
    }
};
