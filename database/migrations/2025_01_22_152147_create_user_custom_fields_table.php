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
        Schema::create('user_custom_fields', function (Blueprint $table) {
            $table->id();
            $table->string('field_label'); // Label of the field (e.g., "Upload a photo", "Enter bio")
            $table->enum('field_type', ['text', 'image', 'multiple_choice']); // Add 'multiple_choice' as a field type
            $table->text('options')->nullable(); // JSON field to store multiple choice options
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_custom_fields');
    }
};
