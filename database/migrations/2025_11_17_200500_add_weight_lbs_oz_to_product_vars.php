<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_vars', function (Blueprint $table) {
            if (!Schema::hasColumn('product_vars', 'weight_lbs')) {
                $table->unsignedSmallInteger('weight_lbs')->default(0)->after('weight');
            }
            if (!Schema::hasColumn('product_vars', 'weight_oz')) {
                $table->unsignedTinyInteger('weight_oz')->default(0)->after('weight_lbs');
            }
        });

        // Backfill from existing weight decimal into lbs/oz
        $rows = DB::table('product_vars')->select('var_id', 'weight')->get();
        foreach ($rows as $row) {
            $w = $row->weight ?? 0;
            $lbs = 0; $oz = 0;
            try {
                $str = number_format((float)$w, 2, '.', '');
                [$int, $frac] = array_pad(explode('.', $str, 2), 2, '0');
                $int = (int)$int;
                $frac = (int)substr(str_pad($frac, 2, '0', STR_PAD_RIGHT), 0, 2);
                if ($frac > 15) {
                    // Interpret as decimal pounds
                    $decimal = (float)$w;
                    $lbs = (int)floor($decimal);
                    $oz = (int)round(($decimal - $lbs) * 16);
                    if ($oz >= 16) { $lbs += 1; $oz = 0; }
                } else {
                    $lbs = max(0, $int);
                    $oz = max(0, min(15, $frac));
                }
            } catch (\Throwable $e) {
                $lbs = 0; $oz = 0;
            }
            DB::table('product_vars')->where('var_id', $row->var_id)->update([
                'weight_lbs' => $lbs,
                'weight_oz' => $oz,
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('product_vars', function (Blueprint $table) {
            if (Schema::hasColumn('product_vars', 'weight_oz')) {
                $table->dropColumn('weight_oz');
            }
            if (Schema::hasColumn('product_vars', 'weight_lbs')) {
                $table->dropColumn('weight_lbs');
            }
        });
    }
};
