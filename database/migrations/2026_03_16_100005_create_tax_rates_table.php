<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tax_rates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('country', 2)->default('AU');
            $table->string('state')->nullable();
            $table->decimal('rate', 5, 2);
            $table->integer('priority')->default(1);
            $table->boolean('compound')->default(false);
            $table->boolean('shipping')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->string('tax_name')->nullable()->after('tax_total');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('tax_name');
        });
        Schema::dropIfExists('tax_rates');
    }
};
