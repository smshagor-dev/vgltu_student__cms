<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('push_subscriptions')) {
            return;
        }

        Schema::table('push_subscriptions', function (Blueprint $table) {
            if (! Schema::hasColumn('push_subscriptions', 'subscribable_id')) {
                $table->unsignedBigInteger('subscribable_id')->nullable()->after('id');
            }

            if (! Schema::hasColumn('push_subscriptions', 'subscribable_type')) {
                $table->string('subscribable_type')->nullable()->after('subscribable_id');
            }
        });

        DB::table('push_subscriptions')
            ->whereNull('subscribable_id')
            ->whereNotNull('user_id')
            ->update([
                'subscribable_id' => DB::raw('user_id'),
                'subscribable_type' => addslashes(User::class),
            ]);

        Schema::table('push_subscriptions', function (Blueprint $table) {
            if (! Schema::hasColumn('push_subscriptions', 'user_agent')) {
                $table->string('user_agent')->nullable()->after('content_encoding');
            }
        });

        if (! $this->hasCompositeIndex('push_subscriptions', 'push_subscriptions_subscribable_index')) {
            Schema::table('push_subscriptions', function (Blueprint $table) {
                $table->index(['subscribable_id', 'subscribable_type'], 'push_subscriptions_subscribable_index');
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('push_subscriptions')) {
            return;
        }

        if ($this->hasCompositeIndex('push_subscriptions', 'push_subscriptions_subscribable_index')) {
            Schema::table('push_subscriptions', function (Blueprint $table) {
                $table->dropIndex('push_subscriptions_subscribable_index');
            });
        }
    }

    private function hasCompositeIndex(string $table, string $indexName): bool
    {
        $connection = Schema::getConnection();
        $driver = $connection->getDriverName();

        if ($driver === 'mysql') {
            $result = DB::selectOne("SHOW INDEX FROM {$table} WHERE Key_name = ?", [$indexName]);

            return $result !== null;
        }

        return false;
    }
};
