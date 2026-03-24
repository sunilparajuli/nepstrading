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
        Schema::table('orders', function (Blueprint $table) {
            // Billing
            $table->string('billing_first_name')->nullable()->after('user_id');
            $table->string('billing_last_name')->nullable()->after('billing_first_name');
            $table->string('billing_address')->nullable()->after('billing_last_name');
            $table->string('billing_city')->nullable()->after('billing_address');
            $table->string('billing_postcode')->nullable()->after('billing_city');
            $table->string('billing_phone')->nullable()->after('billing_postcode');
            $table->string('billing_email')->nullable()->after('billing_phone');

            // Shipping
            $table->string('shipping_first_name')->nullable()->after('billing_email');
            $table->string('shipping_last_name')->nullable()->after('shipping_first_name');
            $table->string('shipping_address')->nullable()->after('shipping_last_name');
            $table->string('shipping_city')->nullable()->after('shipping_address');
            $table->string('shipping_postcode')->nullable()->after('shipping_city');

            $table->text('order_notes')->nullable()->after('shipping_postcode');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'billing_first_name', 'billing_last_name', 'billing_address', 
                'billing_city', 'billing_postcode', 'billing_phone', 'billing_email',
                'shipping_first_name', 'shipping_last_name', 'shipping_address', 
                'shipping_city', 'shipping_postcode', 'order_notes'
            ]);
        });
    }
};
