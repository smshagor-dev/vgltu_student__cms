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
        Schema::table('user_custom_fields', function (Blueprint $table) {
            $table->enum('field_type', ['text', 'image', 'multiple_choice'])->change(); // Update field_type to include multiple_choice
            $table->text('options')->nullable()->after('field_type'); // Add the options column
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_custom_fields', function (Blueprint $table) {
            $table->enum('field_type', ['text', 'image'])->change(); // Revert field_type
            $table->dropColumn('options'); // Remove the options column
        });
    }
};
