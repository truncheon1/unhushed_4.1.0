<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Exception;

class MigrateHcwpRoleToProductAssignment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:hcwp-role
                            {--dry-run : Run the migration without actually inserting data}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate users with role 6 (hcwp) to product_assignments and remove role assignments';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');

        if ($dryRun) {
            $this->info('Running in DRY RUN mode - no data will be modified');
        }

        try {
            // Get all users assigned to role 6 (hcwp)
            $userRoles = DB::table('user_roles')
                ->where('role_id', 6)
                ->get();

            if ($userRoles->isEmpty()) {
                $this->info('No users found with role 6 (hcwp).');
                return 0;
            }

            $this->info("Found {$userRoles->count()} users with hcwp role (role_id: 6).");

            $migrated = 0;
            $skipped = 0;
            $errors = [];

            DB::beginTransaction();

            foreach ($userRoles as $userRole) {
                // Check if this assignment already exists
                $exists = DB::table('product_assignments')
                    ->where('user_id', $userRole->user_id)
                    ->where('product_id', 13)
                    ->where('var_id', 13)
                    ->where('category', 7)
                    ->exists();

                if ($exists) {
                    $skipped++;
                    $this->warn("Skipped: Assignment already exists for user_id: {$userRole->user_id}");
                    continue;
                }

                // Insert product assignment for HCWP training
                $data = [
                    'user_id' => $userRole->user_id,
                    'product_id' => 13,
                    'var_id' => 13,
                    'category' => 7,
                    'active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                if (!$dryRun) {
                    DB::table('product_assignments')->insert($data);
                }

                $migrated++;

                $this->info("Migrated user_id: {$userRole->user_id} to product_assignments");
            }

            // Delete role 6 assignments from user_roles
            if (!$dryRun) {
                $deleted = DB::table('user_roles')
                    ->where('role_id', 6)
                    ->delete();

                $this->info("\nDeleted {$deleted} role assignments for role 6 (hcwp)");
            } else {
                $this->info("\n[Dry run] Would delete {$userRoles->count()} role assignments for role 6 (hcwp)");
            }

            if (!$dryRun) {
                DB::commit();
                $this->info("\n✓ Migration completed successfully!");
            } else {
                DB::rollBack();
                $this->info("\n✓ Dry run completed - no data was modified");
            }

            $this->newLine();
            $this->info("Summary:");
            $this->info("Total users processed: {$userRoles->count()}");
            $this->info("Successfully migrated: {$migrated}");
            $this->info("Skipped (already exists): {$skipped}");

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
