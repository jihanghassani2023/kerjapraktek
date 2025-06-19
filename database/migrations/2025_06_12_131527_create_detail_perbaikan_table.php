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
            $table->string('perbaikan_id', 11);
            $table->decimal('harga', 15, 2);
            $table->text('process_step')->nullable();

            // Garansi details boleh kosong (nullable)
            $table->string('garansi_sparepart', 100)->nullable();
            $table->enum('garansi_periode', ['Tidak ada garansi', '1 Bulan', '12 Bulan'])->nullable();

            $table->timestamps();

            // Foreign key
            $table->foreign('perbaikan_id')->references('id')->on('perbaikan')->onDelete('cascade');

            // Indexes untuk optimasi query
            $table->index(['perbaikan_id', 'created_at']);
            $table->index(['perbaikan_id', 'garansi_sparepart']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_perbaikan');
    }
};
