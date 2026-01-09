<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if table exists and needs fixing
        if(Schema::hasTable('product_options_vars_junction')){
            // Backup existing data
            $existingData = DB::table('product_options_vars_junction')->get();
            
            // Drop and recreate table with correct schema
            Schema::dropIfExists('product_options_vars_junction');
            
            Schema::create('product_options_vars_junction', function (Blueprint $table) {
                $table->bigIncrements('id'); // Auto-increment PRIMARY KEY
                $table->unsignedBigInteger('var_id')->index(); // Foreign key to product_vars
                $table->unsignedBigInteger('value_id')->index(); // Foreign key to product_options_values
                $table->timestamps();
                
                // Prevent duplicate var_id + value_id combinations
                $table->unique(['var_id', 'value_id']);
            });
            
            // Restore data (only unique var_id + value_id combinations)
            if($existingData->count() > 0){
                $uniqueRows = [];
                $seen = [];
                foreach($existingData as $row){
                    $key = $row->var_id . '-' . $row->value_id;
                    if(!isset($seen[$key])){
                        $seen[$key] = true;
                        $uniqueRows[] = [
                            'var_id' => $row->var_id,
                            'value_id' => $row->value_id,
                            'created_at' => $row->created_at ?? now(),
                            'updated_at' => $row->updated_at ?? now(),
                        ];
                    }
                }
                
                if(count($uniqueRows) > 0){
                    DB::table('product_options_vars_junction')->insert($uniqueRows);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Backup existing data
        $existingData = Schema::hasTable('product_options_vars_junction') 
            ? DB::table('product_options_vars_junction')->get() 
            : collect();
        
        // Drop and recreate with old schema
        Schema::dropIfExists('product_options_vars_junction');
        
        Schema::create('product_options_vars_junction', function (Blueprint $table) {
            $table->bigIncrements('var_id');
            $table->integer('value_id')->nullable()->index();
            $table->timestamps();
        });
        
        // Restore data (old schema can't handle multiple values per var_id properly)
        if($existingData->count() > 0){
            foreach($existingData as $row){
                DB::table('product_options_vars_junction')->insert([
                    'var_id' => $row->var_id,
                    'value_id' => $row->value_id,
                    'created_at' => $row->created_at ?? now(),
                    'updated_at' => $row->updated_at ?? now(),
                ]);
            }
        }
    }
};
