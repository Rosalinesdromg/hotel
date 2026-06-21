<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('menus');

        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category');
            $table->decimal('price', 10, 2);
            $table->integer('stock')->default(0);
            $table->boolean('is_available')->default(true);
            $table->string('image')->nullable();
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

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('menu_id')->constrained()->cascadeOnDelete();
            $table->integer('quantity');
            $table->decimal('price', 10, 2);
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('menus');
        Schema::enableForeignKeyConstraints();
    }
};