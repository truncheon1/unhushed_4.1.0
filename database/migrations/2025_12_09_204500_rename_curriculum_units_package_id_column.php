<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('curriculum_units', function (Blueprint $table) {
            if (Schema::hasColumn('curriculum_units', 'package_id')) {
                $table->renameColumn('package_id', 'product_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('curriculum_units', function (Blueprint $table) {
            if (Schema::hasColumn('curriculum_units', 'product_id')) {
                $table->renameColumn('product_id', 'package_id');
            }
        });
    }
};
