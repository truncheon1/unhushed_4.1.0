<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Exception;

class MigratePackagesAssignedData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:packages-assigned
                            {--dry-run : Run the migration without actually inserting data}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate data from packages_assigneds to product_assignments';

    /**
     * Product ID mapping from old packages_assigneds to new product_assignments
     * Organized by type (0 = curriculum, 4 = training)
     *
     * @var array
     */
    protected $productMapping = [
        0 => [ // Curriculum (type 0 -> category 1)
            2 => ['product_id' => 2, 'var_id' => ''],
            3 => ['product_id' => 3, 'var_id' => ''],
            4 => ['product_id' => 4, 'var_id' => ''],
        ],
        4 => [ // Training (type 4 -> category 7)
            1 => ['product_id' => 5, 'var_id' => 5],
            3 => ['product_id' => 7, 'var_id' => 7],
            6 => ['product_id' => 9, 'var_id' => 9],
            7 => ['product_id' => 10, 'var_id' => 10],
            8 => ['product_id' => 11, 'var_id' => 11],
            9 => ['product_id' => 12, 'var_id' => 12],
            11 => ['product_id' => 13, 'var_id' => 13],
            12 => ['product_id' => 14, 'var_id' => 14],
            13 => ['product_id' => 44, 'var_id' => 44],
            14 => ['product_id' => 45, 'var_id' => 45],
            15 => ['product_id' => 46, 'var_id' => 46],
            16 => ['product_id' => 47, 'var_id' => 43],
            17 => ['product_id' => 48, 'var_id' => 44],
            18 => ['product_id' => 59, 'var_id' => 53],
            59 => ['product_id' => 59, 'var_id' => 53],
        ],
    ];

    /**
     * Type to category mapping
     *
     * @var array
     */
    protected $categoryMapping = [
        0 => 1,  // Curriculum
        4 => 7,  // Training
    ];

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');

        if ($dryRun) {
            $this->info('Running in DRY RUN mode - no data will be inserted');
        }

        try {
            // Get active records from packages_assigneds
            $records = DB::table('packages_assigneds')
                ->where('active', 1)
                ->get();

            if ($records->isEmpty()) {
                $this->info('No active records found in packages_assigneds table.');
                return 0;
            }

            $this->info("Found {$records->count()} active records to migrate.");

            $migrated = 0;
            $skipped = 0;
            $errors = [];

            DB::beginTransaction();

            foreach ($records as $record) {
                // Check if type has a category mapping
                if (!isset($this->categoryMapping[$record->type])) {
                    $skipped++;
                    $errors[] = "Skipped: No category mapping found for type {$record->type} (user_id: {$record->user_id}, product_id: {$record->product_id})";
                    continue;
                }

                // Check if product_id has a mapping for this type
                if (!isset($this->productMapping[$record->type][$record->product_id])) {
                    $skipped++;
                    $errors[] = "Skipped: No mapping found for type {$record->type}, product_id {$record->product_id} (user_id: {$record->user_id})";
                    continue;
                }

                $category = $this->categoryMapping[$record->type];
                $mapping = $this->productMapping[$record->type][$record->product_id];

                // Check if this assignment already exists
                $exists = DB::table('product_assignments')
                    ->where('user_id', $record->user_id)
                    ->where('product_id', $mapping['product_id'])
                    ->where('category', $category)
                    ->exists();

                if ($exists) {
                    $skipped++;
                    $this->warn("Skipped: Assignment already exists (user_id: {$record->user_id}, product_id: {$mapping['product_id']}, category: {$category})");
                    continue;
                }

                // Prepare data for insertion
                $data = [
                    'user_id' => $record->user_id,
                    'product_id' => $mapping['product_id'],
                    'var_id' => $mapping['var_id'],
                    'category' => $category,
                    'created_at' => $record->created_at,
                    'updated_at' => now(),
                ];

                if (!$dryRun) {
                    DB::table('product_assignments')->insert($data);
                }

                $migrated++;

                if ($migrated % 100 == 0) {
                    $this->info("Migrated {$migrated} records...");
                }
            }

            if (!$dryRun) {
                DB::commit();
                $this->info("\n✓ Migration completed successfully!");
            } else {
                DB::rollBack();
                $this->info("\n✓ Dry run completed - no data was inserted");
            }

            $this->info("Total records processed: {$records->count()}");
            $this->info("Successfully migrated: {$migrated}");
            $this->info("Skipped: {$skipped}");

            if (!empty($errors)) {
                $this->newLine();
                $this->warn("Errors/Warnings:");
                foreach ($errors as $error) {
                    $this->warn("  - {$error}");
                }
            }

            return 0;

        } catch (Exception $e) {
            DB::rollBack();
            $this->error("Migration failed: " . $e->getMessage());
            $this->error($e->getTraceAsString());
            return 1;
        }
    }
}
