<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
    $table->string('name', 50);       // Dari 255 menjadi 100
    $table->string('email', 100);      // Dari 255 menjadi 100
    $table->string('password', 100);   // Dari 255 menjadi 100 (masih cukup untuk hash)
    $table->text('alamat')->nullable();
    $table->string('jabatan', 50);     // Dari 255 menjadi 50
    $table->enum('role', ['admin', 'kepala_toko', 'teknisi', 'kepala teknisi', 'user'])->default('user');
    $table->rememberToken();
    $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};
