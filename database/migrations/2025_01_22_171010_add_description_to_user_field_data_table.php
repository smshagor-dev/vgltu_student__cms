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
        Schema::table('user_field_data', function (Blueprint $table) {
            $table->json('description')->nullable()->after('value'); // Add the new column
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_field_data', function (Blueprint $table) {
            $table->dropColumn('description'); // Rollback the column if necessary
        });
    }
};
