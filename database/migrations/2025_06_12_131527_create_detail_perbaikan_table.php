<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_perbaikan', function (Blueprint $table) {
           $table->increments('id');
            $table->string('perbaikan_id', 11);

            $table->text('process_step')->nullable();


            $table->timestamps();

            // Foreign key
            $table->foreign('perbaikan_id')->references('id')->on('perbaikan')->onDelete('cascade');

            // Indexes untuk optimasi query
            $table->index(['perbaikan_id', 'created_at']);

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_perbaikan');
    }
};
