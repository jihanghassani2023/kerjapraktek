<?php
// database/migrations/2025_06_12_131527_create_detail_perbaikan_table.php
// FIXED: Allow nullable fields untuk flexible garansi management

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

            // Data utama perbaikan
            $table->string('nama_device', 100);
            $table->string('kategori_device', 50)->nullable();
            $table->string('masalah', 200);
            $table->text('tindakan_perbaikan');
            $table->decimal('harga', 15, 2);

            // Garansi (untuk backward compatibility)
            $table->string('garansi', 50)->nullable();

            // FIXED: Process step boleh kosong (nullable)
            $table->text('process_step')->nullable();

            // FIXED: Garansi details boleh kosong (nullable) - user bisa hapus semua garansi
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
