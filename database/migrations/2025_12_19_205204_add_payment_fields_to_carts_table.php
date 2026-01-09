<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        //Give the moving column a temporary name:
        Schema::table('carts', function($table)
        {
            $table->renameColumn('pp_id', 'pp_id_old');
        });

        //Add a new column with the regular name:
        Schema::table('carts', function(Blueprint $table)
        {
            $table->string('payment_id')->nullable()->after('id');
            $table->timestamp('completed_at')->nullable()->after('created_at');
        });

        //Copy the data across to the new column:
        DB::table('carts')->update([
            'payment_id' => DB::raw('pp_id_old')
        ]);

        //Remove the old column:
        Schema::table('carts', function(Blueprint $table)
        {
            $table->dropColumn('pp_id_old');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->dropColumn(['completed_at']);
        });
    }
};
