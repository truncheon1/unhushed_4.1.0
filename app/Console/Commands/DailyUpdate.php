<?php
namespace App\Console\Commands;
use App\Models\User;
use App\Services\ActiveCampaignService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DailyUpdate extends Command
{
    /* The name and signature of the console command. @var string */
    protected $signature = 'ac:update';

    /* The console command description. @var string */
    protected $description = 'Daily Update for Active Campaign Users';

    /* Create a new command instance. @return void */
    public function __construct(){
        parent::__construct();
    }

    /* Execute the console command. @return int */
    public function handle(){
        $acService = new ActiveCampaignService();

        // Get all users with last_login data
        $users = User::whereNotNull('last_login')->get();

        if ($users->isEmpty()) {
            $this->info('No users with last_login found');
            return 0;
        }

        $updatedCount = 0;
        foreach ($users as $user) {
            try {
                // Extract just the date portion from last_login (format: YYYY-MM-DD)
                $lastLoginDate = Carbon::parse($user->last_login)->toDateString();

                // Update user's last login in AC
                $result = $acService->syncUserUpdate($user, [
                    'user_id' => $user->id,
                    'org_id' => $user->org_id,
                    'last_login' => $lastLoginDate,
                ]);

                if ($result) {
                    $updatedCount++;
                    $this->info("Updated: {$user->email}");
                }
            } catch (\Exception $e) {
                $this->error("Failed to update {$user->email}: {$e->getMessage()}");
            }
        }

        $this->info("Daily update complete: {$updatedCount} users synced");
        return 0;
    }
}
