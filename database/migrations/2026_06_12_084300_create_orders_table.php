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
    Schema::create('orders', function (Blueprint $table) {
        $table->id();
        $table->string('order_code')->unique();
        $table->foreignId('booking_id')->nullable()->constrained()->nullOnDelete(); // null = walk-in
        $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();    // kasir yang input
        $table->enum('type', ['walkin', 'room_service']);
        $table->decimal('total_price', 10, 2);
        $table->enum('payment_method', ['cash', 'debit', 'charge_to_room'])->nullable();
        $table->enum('status', ['pending', 'paid', 'void'])->default('pending');
        $table->foreignId('voided_by')->nullable()->constrained('users')->nullOnDelete();
        $table->softDeletes();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
