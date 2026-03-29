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
        if (!Schema::hasColumn('products', 'meta_title')) {
            Schema::table('products', function (Blueprint $table) {
                $table->string('meta_title')->nullable()->after('cart_disabled_message');
                $table->text('meta_description')->nullable()->after('meta_title');
                $table->string('meta_keywords')->nullable()->after('meta_description');
                $table->string('og_title')->nullable()->after('meta_keywords');
                $table->text('og_description')->nullable()->after('og_title');
                $table->string('og_image')->nullable()->after('og_description');
            });
        }

        if (!Schema::hasColumn('categories', 'meta_title')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->string('meta_title')->nullable()->after('image');
                $table->text('meta_description')->nullable()->after('meta_title');
                $table->string('meta_keywords')->nullable()->after('meta_description');
                $table->string('og_title')->nullable()->after('meta_keywords');
                $table->text('og_description')->nullable()->after('og_title');
                $table->string('og_image')->nullable()->after('og_description');
            });
        }

        if (!Schema::hasColumn('pages', 'meta_keywords')) {
            Schema::table('pages', function (Blueprint $table) {
                $table->string('meta_keywords')->nullable()->after('meta_description');
                $table->string('og_title')->nullable()->after('meta_keywords');
                $table->text('og_description')->nullable()->after('og_title');
                $table->string('og_image')->nullable()->after('og_description');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['meta_title', 'meta_description', 'meta_keywords', 'og_title', 'og_description', 'og_image']);
        });
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['meta_title', 'meta_description', 'meta_keywords', 'og_title', 'og_description', 'og_image']);
        });
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn(['meta_keywords', 'og_title', 'og_description', 'og_image']);
        });
    }
};
