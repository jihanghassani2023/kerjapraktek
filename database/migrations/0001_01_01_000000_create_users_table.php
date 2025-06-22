<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name', 50);
            $table->string('email', 100)->unique();
            $table->string('password', 100);
            $table->text('alamat')->nullable();
            $table->string('jabatan', 50);
            $table->enum('role', ['admin', 'kepala_toko', 'teknisi', 'kepala teknisi', 'user'])->default('user');
            $table->rememberToken();
            $table->timestamps();
        });

        DB::statement('ALTER TABLE users AUTO_INCREMENT = 10001');
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
