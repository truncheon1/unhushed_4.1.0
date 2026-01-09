<?php
namespace App\Console\Commands;
use App\Models\Cart;
use App\Models\User;
use App\Services\ActiveCampaignService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CartAbandoned extends Command
{
    /*The name and signature of the console command. @var string */
    protected $signature = 'ac:abandoned';

    /* The console command description. @var string */
    protected $description = '30 min scan for abandoned carts by users';

    /* Create a new command instance. @return void */
    public function __construct(){
        parent::__construct();
    }

    /* Execute the console command. @return int */
    public function handle(){
        $acService = new ActiveCampaignService();

        //query `carts` table for abandoned carts (updated 30-61 minutes ago)
        $carts = Cart::where([
            ['completed', '!=', Cart::CART_COMPLETE],
            ['user_id', '!=', 0],
            ['updated_at', '>', Carbon::now()->subMinutes(61)->toDateTimeString()],
            ['updated_at', '<', Carbon::now()->subMinutes(29)->toDateTimeString()]
        ])
        ->orderBy('created_at', 'DESC')->get();

        if ($carts->isEmpty()) {
            $this->info('No abandoned carts found');
            return 0;
        }

        $processedCount = 0;
        foreach ($carts as $cart) {
            try {
                $user = User::find($cart->user_id);
                if (!$user) {
                    continue;
                }

                // Add abandoned cart tag to trigger automation in AC
                $result = $acService->addTagByEmail(
                    $user->email, 
                    ActiveCampaignService::TAG_ABANDONED_CART
                );

                if ($result) {
                    $processedCount++;
                    $this->info("Tagged abandoned cart: {$user->email}");
                }
            } catch (\Exception $e) {
                $this->error("Failed to process cart {$cart->id}: {$e->getMessage()}");
            }
        }

        $this->info("Processed {$processedCount} abandoned carts");
        return 0;
    }
}

