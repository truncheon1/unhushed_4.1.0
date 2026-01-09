<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('active_subscriptions', function (Blueprint $table) {
            if (Schema::hasColumn('active_subscriptions', 'package_id')) {
                $table->renameColumn('package_id', 'product_id');
            }
            if (Schema::hasColumn('active_subscriptions', 'type')) {
                $table->renameColumn('type', 'category');
            }
        });
        
        // Update category values: 0 -> 1 (curriculum), 4 -> 7 (training)
        if (Schema::hasColumn('active_subscriptions', 'category')) {
            DB::table('active_subscriptions')->where('category', 0)->update(['category' => 1]);
            DB::table('active_subscriptions')->where('category', 4)->update(['category' => 7]);
        }
    }

    public function down(): void
    {
        // Revert category values: 1 -> 0, 7 -> 4
        if (Schema::hasColumn('active_subscriptions', 'category')) {
            DB::table('active_subscriptions')->where('category', 7)->update(['category' => 4]);
            DB::table('active_subscriptions')->where('category', 1)->update(['category' => 0]);
        }
        
        Schema::table('active_subscriptions', function (Blueprint $table) {
            if (Schema::hasColumn('active_subscriptions', 'product_id')) {
                $table->renameColumn('product_id', 'package_id');
            }
            if (Schema::hasColumn('active_subscriptions', 'category')) {
                $table->renameColumn('category', 'type');
            }
        });
    }
};
