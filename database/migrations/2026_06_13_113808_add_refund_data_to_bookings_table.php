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
    Schema::table('bookings', function (Blueprint $table) {
        if (!Schema::hasColumn('bookings', 'refund_data')) {
            $table->text('refund_data')->nullable()->after('refund_reason');
        }
    });
}

public function down()
{
    Schema::table('bookings', function (Blueprint $table) {
        $table->dropColumn('refund_data');
    });
}
};
