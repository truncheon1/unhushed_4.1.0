<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Exception;

class MigrateRecentActiveSubscriptions extends Command
{
    protected $signature = 'migrate:recent-active-subscriptions
                            {--dry-run : Run the migration without actually inserting data}';

    protected $description = 'Migrate recent records (on or after 2025-11-21 18:04:45) from active_subscriptions_old to active_subscriptions';

    public function handle()
    {
        $dryRun = $this->option('dry-run');
        $cutoffDate = '2025-11-21 18:04:45';

        if ($dryRun) {
            $this->info('Running in DRY RUN mode - no data will be inserted');
        }

        try {
            // Check if old table exists
            if (!DB::getSchemaBuilder()->hasTable('active_subscriptions_old')) {
                $this->error('Table active_subscriptions_old does not exist.');
                return 1;
            }

            // Get records created on or after cutoff date
            $records = DB::table('active_subscriptions_old')
                ->where('created_at', '>=', $cutoffDate)
                ->get();

            if ($records->isEmpty()) {
                $this->info("No records found in active_subscriptions_old created on or after {$cutoffDate}.");
                return 0;
            }

            $this->info("Found {$records->count()} records to migrate (created on or after {$cutoffDate}).");

            $migrated = 0;
            $skipped = 0;
            $errors = [];

            DB::beginTransaction();

            foreach ($records as $record) {
                // Determine var_id based on type
                $varId = ($record->type == 1) ? 0 : null;
                
                // Map type to category (type 1 stays as category 1)
                $category = $record->type;

                // Check if this subscription already exists
                $exists = DB::table('active_subscriptions')
                    ->where('org_id', $record->org_id)
                    ->where('product_id', $record->package_id)
                    ->where('category', $category)
                    ->exists();

                if ($exists) {
                    $skipped++;
                    $this->warn("Skipped: Subscription already exists (org_id: {$record->org_id}, product_id: {$record->package_id}, category: {$category})");
                    continue;
                }

                // Prepare data for insertion
                $data = [
                    'org_id' => $record->org_id,
                    'product_id' => $record->package_id,  // package_id maps to product_id
                    'var_id' => $varId,
                    'category' => $category,
                    'total' => $record->total,
                    'used' => $record->used,
                    'status' => $record->status,
                    'created_at' => $record->created_at,
                    'updated_at' => now(),
                ];

                if (!$dryRun) {
                    try {
                        DB::table('active_subscriptions')->insert($data);
                        $migrated++;
                    } catch (Exception $e) {
                        $errors[] = "Failed to insert: org_id {$record->org_id}, product_id {$record->package_id} - Error: " . $e->getMessage();
                        $skipped++;
                    }
                } else {
                    $migrated++;
                }

                if ($migrated % 10 == 0) {
                    $this->info("Processed {$migrated} records...");
                }
            }

            if (!$dryRun) {
                DB::commit();
                $this->info("\n✓ Migration completed successfully!");
            } else {
                DB::rollBack();
                $this->info("\n✓ Dry run completed - no data was inserted");
            }

            $this->newLine();
            $this->info("Summary:");
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
