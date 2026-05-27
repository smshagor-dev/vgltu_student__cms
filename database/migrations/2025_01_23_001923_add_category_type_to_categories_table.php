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
        if (!Schema::hasTable('categories') || !Schema::hasTable('category_types') || Schema::hasColumn('categories', 'category_type_id')) {
            return;
        }

        Schema::table('categories', function (Blueprint $table) {
            $table->foreignId('category_type_id')->constrained('category_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('categories') || !Schema::hasColumn('categories', 'category_type_id')) {
            return;
        }

        Schema::table('categories', function (Blueprint $table) {
            $table->dropForeign(['category_type_id']);
            $table->dropColumn('category_type_id');
        });
    }
};
