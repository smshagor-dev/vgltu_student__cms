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
        if (!Schema::hasTable('user_field_data') || !Schema::hasColumn('user_field_data', 'descriptions')) {
            return;
        }

        Schema::table('user_field_data', function (Blueprint $table) {
            $table->json('descriptions')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('user_field_data') || !Schema::hasColumn('user_field_data', 'descriptions')) {
            return;
        }

        Schema::table('user_field_data', function (Blueprint $table) {
            $table->text('descriptions')->change();
        });
    }
};
