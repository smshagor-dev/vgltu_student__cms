<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('students_data')) {
            return;
        }

        Schema::table('students_data', function (Blueprint $table) {
            if (! Schema::hasColumn('students_data', 'visa_reminder_90_sent_at')) {
                $table->timestamp('visa_reminder_90_sent_at')->nullable()->after('green_card_photo');
            }

            if (! Schema::hasColumn('students_data', 'visa_reminder_75_sent_at')) {
                $table->timestamp('visa_reminder_75_sent_at')->nullable()->after('visa_reminder_90_sent_at');
            }

            if (! Schema::hasColumn('students_data', 'visa_reminder_60_sent_at')) {
                $table->timestamp('visa_reminder_60_sent_at')->nullable()->after('visa_reminder_75_sent_at');
            }

            if (! Schema::hasColumn('students_data', 'visa_overdue_10_sent_at')) {
                $table->timestamp('visa_overdue_10_sent_at')->nullable()->after('visa_reminder_60_sent_at');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('students_data')) {
            return;
        }

        Schema::table('students_data', function (Blueprint $table) {
            $columns = [
                'visa_reminder_90_sent_at',
                'visa_reminder_75_sent_at',
                'visa_reminder_60_sent_at',
                'visa_overdue_10_sent_at',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('students_data', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
