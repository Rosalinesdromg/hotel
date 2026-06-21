<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up()
{
    Schema::disableForeignKeyConstraints();

    Schema::dropIfExists('rooms');
    Schema::dropIfExists('room_types');

    Schema::create('room_types', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->text('description')->nullable();
        $table->integer('capacity');
        $table->decimal('base_price', 10, 2);
        $table->decimal('weekend_price', 10, 2)->nullable();
        $table->string('image')->nullable();
        $table->timestamps();
    });

    Schema::create('rooms', function (Blueprint $table) {
        $table->id();
        $table->foreignId('room_type_id')->constrained()->cascadeOnDelete();
        $table->string('room_number')->unique();
        $table->enum('status', ['available', 'occupied', 'dirty', 'maintenance'])->default('available');
        $table->timestamps();
    });

    Schema::enableForeignKeyConstraints();
}

    public function down()
    {
        Schema::dropIfExists('room_types');
    }
};