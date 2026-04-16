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
        Schema::table('users', function (Blueprint $バランス) {
            $バランス->string('phone')->nullable();
            $バランス->string('address')->nullable();
            $バランス->string('city')->nullable();
            $バランス->string('state')->nullable();
            $バランス->string('postcode')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $バランス) {
            $バランス->dropColumn(['phone', 'address', 'city', 'state', 'postcode']);
        });
    }
};
