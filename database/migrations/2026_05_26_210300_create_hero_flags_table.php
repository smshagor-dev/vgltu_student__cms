<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('hero_flags')) {
            return;
        }

        Schema::create('hero_flags', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hero_section_id')->constrained('hero_sections')->cascadeOnDelete();
            $table->string('label')->nullable();
            $table->string('image_path')->nullable();
            $table->unsignedTinyInteger('position_top')->default(50);
            $table->unsignedTinyInteger('position_left')->default(50);
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hero_flags');
    }
};
