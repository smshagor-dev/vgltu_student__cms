<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('website_settings')) {
            return;
        }

        Schema::create('website_settings', function (Blueprint $table) {
            $table->id();
            $table->string('site_name')->nullable();
            $table->string('logo_path')->nullable();
            $table->string('favicon_path')->nullable();
            $table->string('contact_button_text')->nullable();
            $table->string('contact_button_link')->nullable();
            $table->string('default_language', 20)->nullable();
            $table->json('available_languages')->nullable();
            $table->string('topbar_location')->nullable();
            $table->string('class_routine_text')->nullable();
            $table->string('class_routine_link')->nullable();
            $table->string('university_profile_text')->nullable();
            $table->string('university_profile_link')->nullable();
            $table->string('search_placeholder')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('website_settings');
    }
};
