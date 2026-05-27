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
            // Change the 'value' column to JSON to support storing multiple selected options
            $table->json('value')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_field_data', function (Blueprint $table) {
            // Revert the 'value' column back to text
            $table->text('value')->nullable()->change();
        });
    }
};
