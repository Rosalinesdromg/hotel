<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Hapus tabel rooms lama yang kosong/salah
        Schema::dropIfExists('rooms');

        // Buat ulang dengan struktur yang benar
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_type_id')->constrained()->cascadeOnDelete();
            $table->string('room_number')->unique();
            $table->enum('status', ['available', 'occupied', 'dirty', 'maintenance'])->default('available');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rooms');
    }
};