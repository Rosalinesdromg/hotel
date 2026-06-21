<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::dropIfExists('orders');
        Schema::dropIfExists('bookings');

        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code')->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('room_id')->constrained()->cascadeOnDelete();
            $table->enum('package', ['room_only', 'with_breakfast', 'full_package']);
            $table->date('check_in');
            $table->date('check_out');
            $table->integer('guest_count');
            $table->boolean('extra_bed')->default(false);
            $table->decimal('total_price', 10, 2);
            $table->decimal('dp_amount', 10, 2)->default(0);
            $table->enum('payment_status', ['unpaid', 'dp', 'paid'])->default('unpaid');
            $table->enum('status', ['pending', 'confirmed', 'checked_in', 'checked_out', 'cancelled'])->default('pending');
            $table->string('refund_status')->nullable();
            $table->text('refund_reason')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_code')->unique();
            $table->foreignId('booking_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('type', ['walkin', 'room_service']);
            $table->decimal('total_price', 10, 2);
            $table->enum('payment_method', ['cash', 'debit', 'charge_to_room'])->nullable();
            $table->enum('status', ['pending', 'paid', 'void'])->default('pending');
            $table->foreignId('voided_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('orders');
        Schema::dropIfExists('bookings');
        Schema::enableForeignKeyConstraints();
    }
};