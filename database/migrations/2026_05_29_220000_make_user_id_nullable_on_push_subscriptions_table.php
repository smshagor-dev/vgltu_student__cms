<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('push_subscriptions') || ! Schema::hasColumn('push_subscriptions', 'user_id')) {
            return;
        }

        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE push_subscriptions MODIFY user_id BIGINT UNSIGNED NULL');
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('push_subscriptions') || ! Schema::hasColumn('push_subscriptions', 'user_id')) {
            return;
        }

        if (DB::getDriverName() === 'mysql') {
            DB::statement('UPDATE push_subscriptions SET user_id = subscribable_id WHERE user_id IS NULL AND subscribable_id IS NOT NULL');
            DB::statement('DELETE FROM push_subscriptions WHERE user_id IS NULL');
            DB::statement('ALTER TABLE push_subscriptions MODIFY user_id BIGINT UNSIGNED NOT NULL');
        }
    }
};
