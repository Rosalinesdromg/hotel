<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('room_types', function (Blueprint $table) {
        $table->id();
        $table->string('name');              // Deluxe, Suite, dll
        $table->text('description')->nullable();
        $table->integer('capacity');         // maks tamu
        $table->decimal('base_price', 10, 2);
        $table->decimal('weekend_price', 10, 2)->nullable();
        $table->string('image')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_types');
    }
};
