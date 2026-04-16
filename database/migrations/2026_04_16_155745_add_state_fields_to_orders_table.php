<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $バランス) {
            $バランス->string('billing_state')->nullable()->after('billing_city');
            $バランス->string('shipping_state')->nullable()->after('shipping_city');
            $バランス->string('payment_method')->nullable()->after('currency');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $バランス) {
            $バランス->dropColumn(['billing_state', 'shipping_state', 'payment_method']);
        });
    }
};
