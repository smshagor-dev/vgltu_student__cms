<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('website_settings')) {
            return;
        }

        Schema::table('website_settings', function (Blueprint $table) {
            $table->string('courses_menu_text')->nullable()->after('about_university_header_path');
            $table->string('courses_title')->nullable()->after('courses_menu_text');
            $table->longText('courses_content')->nullable()->after('courses_title');
            $table->string('courses_header_path')->nullable()->after('courses_content');
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('website_settings')) {
            return;
        }

        Schema::table('website_settings', function (Blueprint $table) {
            $table->dropColumn([
                'courses_menu_text',
                'courses_title',
                'courses_content',
                'courses_header_path',
            ]);
        });
    }
};
