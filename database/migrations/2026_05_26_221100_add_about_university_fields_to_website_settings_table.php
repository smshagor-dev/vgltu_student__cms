<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('website_settings')) {
            return;
        }

        Schema::table('website_settings', function (Blueprint $table) {
            $table->string('about_university_menu_text')->nullable()->after('university_profile_link');
            $table->string('about_university_title')->nullable()->after('about_university_menu_text');
            $table->longText('about_university_content')->nullable()->after('about_university_title');
            $table->string('about_university_header_path')->nullable()->after('about_university_content');
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('website_settings')) {
            return;
        }

        Schema::table('website_settings', function (Blueprint $table) {
            $table->dropColumn([
                'about_university_menu_text',
                'about_university_title',
                'about_university_content',
                'about_university_header_path',
            ]);
        });
    }
};
