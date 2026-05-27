<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('category_photos')) {
            Schema::create('category_photos', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('sub_category_id');
                $table->string('photo_path');
                $table->timestamps();
            });
        }

        // Existing databases may already have the table with an incompatible bigint column.
        DB::statement('ALTER TABLE `category_photos` MODIFY `sub_category_id` INT UNSIGNED NOT NULL');

        $foreignKeyExists = DB::table('information_schema.KEY_COLUMN_USAGE')
            ->where('TABLE_SCHEMA', DB::getDatabaseName())
            ->where('TABLE_NAME', 'category_photos')
            ->where('COLUMN_NAME', 'sub_category_id')
            ->whereNotNull('REFERENCED_TABLE_NAME')
            ->exists();

        if (!$foreignKeyExists) {
            Schema::table('category_photos', function (Blueprint $table) {
                $table->foreign('sub_category_id')->references('id')->on('sub_categories')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('category_photos')) {
            $foreignKeyExists = DB::table('information_schema.KEY_COLUMN_USAGE')
                ->where('TABLE_SCHEMA', DB::getDatabaseName())
                ->where('TABLE_NAME', 'category_photos')
                ->where('COLUMN_NAME', 'sub_category_id')
                ->whereNotNull('REFERENCED_TABLE_NAME')
                ->exists();

            if ($foreignKeyExists) {
                Schema::table('category_photos', function (Blueprint $table) {
                    $table->dropForeign(['sub_category_id']);
                });
            }

            Schema::drop('category_photos');
        }
    }
};
