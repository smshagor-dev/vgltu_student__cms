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
        if (Schema::hasTable('category_types')) {
            return;
        }

        Schema::create('category_types', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // Photo, Video, Students
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('category_types')) {
            Schema::drop('category_types');
        }
    }
};
