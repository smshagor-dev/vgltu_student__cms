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
            $table->enum('field_type', ['text', 'image']); // Define whether it’s text or an image upload
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
