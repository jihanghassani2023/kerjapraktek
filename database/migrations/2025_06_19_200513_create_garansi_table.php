<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('garansi', function (Blueprint $table) {
            $table->id();
            $table->string('perbaikan_id', 11);
            $table->string('sparepart', 100);
            $table->enum('periode', ['Tidak ada garansi', '1 Bulan', '12 Bulan']);
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('perbaikan_id')->references('id')->on('perbaikan')->onDelete('cascade');

            // Index untuk optimasi query
            $table->index(['perbaikan_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('garansi');
    }
};
