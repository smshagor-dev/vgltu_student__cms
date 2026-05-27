<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('hero_sections')) {
            return;
        }

        Schema::create('hero_sections', function (Blueprint $table) {
            $table->id();
            $table->string('badge_text')->nullable();
            $table->string('title');
            $table->text('subtitle')->nullable();
            $table->string('background_image_path')->nullable();
            $table->string('cta_text')->nullable();
            $table->string('cta_link')->nullable();
            $table->string('overlay_start_color', 20)->nullable();
            $table->string('overlay_end_color', 20)->nullable();
            $table->decimal('overlay_opacity', 3, 2)->default(0.68);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hero_sections');
    }
};
