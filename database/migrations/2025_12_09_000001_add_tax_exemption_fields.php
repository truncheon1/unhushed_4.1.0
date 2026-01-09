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
        // Add tax exemption fields to organizations
        Schema::table('organizations', function (Blueprint $table) {
            $table->boolean('tax_exempt')->default(false)->after('type');
            $table->string('tax_exempt_id', 50)->nullable()->after('tax_exempt')->comment('Tax exemption certificate ID');
            $table->string('tax_exempt_type', 50)->nullable()->after('tax_exempt_id')->comment('Type: nonprofit, government, educational, etc.');
            $table->text('tax_exempt_certificate')->nullable()->after('tax_exempt_type')->comment('Path to uploaded certificate');
            $table->date('tax_exempt_expiry')->nullable()->after('tax_exempt_certificate')->comment('Certificate expiration date');
            $table->timestamp('tax_exempt_verified_at')->nullable()->after('tax_exempt_expiry')->comment('When admin verified exemption');
            $table->unsignedBigInteger('tax_exempt_verified_by')->nullable()->after('tax_exempt_verified_at');
            
            $table->foreign('tax_exempt_verified_by')->references('id')->on('users')->onDelete('set null');
        });
        
        // Add tax exemption override to users (for individual exemptions)
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('tax_exempt_override')->default(false)->after('org_id')->comment('Individual tax exemption');
            $table->text('tax_exempt_notes')->nullable()->after('tax_exempt_override')->comment('Admin notes about exemption');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->dropForeign(['tax_exempt_verified_by']);
            $table->dropColumn([
                'tax_exempt',
                'tax_exempt_id',
                'tax_exempt_type',
                'tax_exempt_certificate',
                'tax_exempt_expiry',
                'tax_exempt_verified_at',
                'tax_exempt_verified_by',
            ]);
        });
        
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'tax_exempt_override',
                'tax_exempt_notes',
            ]);
        });
    }
};
