<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('push_subscriptions')) {
            return;
        }

        if (DB::getDriverName() === 'mysql' && Schema::getColumnType('push_subscriptions', 'endpoint') === 'text') {
            DB::statement('ALTER TABLE push_subscriptions MODIFY endpoint VARCHAR(512) NOT NULL');
        }

        if (DB::getDriverName() === 'mysql') {
            $index = DB::selectOne("SHOW INDEX FROM push_subscriptions WHERE Key_name = 'push_subscriptions_endpoint_unique'");

            if (! $index) {
                DB::statement('ALTER TABLE push_subscriptions ADD UNIQUE push_subscriptions_endpoint_unique (endpoint)');
            }
        }
    }

    public function down(): void
    {
        //
    }
};
