<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notification_email_campaigns', function (Blueprint $table) {
            if (! Schema::hasColumn('notification_email_campaigns', 'body_html')) {
                $table->longText('body_html')->nullable()->after('description');
            }
        });
    }

    public function down(): void
    {
        Schema::table('notification_email_campaigns', function (Blueprint $table) {
            if (Schema::hasColumn('notification_email_campaigns', 'body_html')) {
                $table->dropColumn('body_html');
            }
        });
    }
};
