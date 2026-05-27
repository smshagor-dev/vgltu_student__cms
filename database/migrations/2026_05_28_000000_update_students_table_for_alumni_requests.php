<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE students MODIFY degree TEXT NOT NULL");
        DB::statement("ALTER TABLE students MODIFY department TEXT NOT NULL");
        DB::statement("ALTER TABLE students MODIFY pass_year TEXT NOT NULL");

        Schema::table('students', function (Blueprint $table) {
            $table->string('status')->default('approved')->after('pass_year');
            $table->string('source')->default('admin')->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn(['status', 'source']);
        });

        DB::statement("ALTER TABLE students MODIFY degree VARCHAR(255) NOT NULL");
        DB::statement("ALTER TABLE students MODIFY department VARCHAR(255) NOT NULL");
        DB::statement("ALTER TABLE students MODIFY pass_year YEAR NOT NULL");
    }
};
