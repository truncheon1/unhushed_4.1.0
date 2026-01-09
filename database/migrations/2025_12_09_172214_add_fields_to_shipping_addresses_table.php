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
        Schema::table('shipping_addresses', function (Blueprint $table) {
            $table->boolean('default')->default(false)->after('user_id');
            $table->string('phone', 20)->nullable()->after('email');
            $table->string('company')->nullable()->after('name');
            $table->char('country', 2)->default('US')->after('zip');
            $table->string('state_province')->nullable()->after('country');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shipping_addresses', function (Blueprint $table) {
            $table->dropColumn(['default', 'phone', 'company', 'country', 'state_province']);
            $table->dropSoftDeletes();
        });
    }
};
