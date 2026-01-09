<?php
namespace App\Console\Commands;
use App\Models\Cart;
use App\Models\User;
use App\Services\ActiveCampaignService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CartShipped extends Command
{
    /*The name and signature of the console command. @var string */
    protected $signature = 'ac:shipped';

    /* The console command description. @var string */
    protected $description = '30 min scan for orders marked as shipped';

    /* Create a new command instance. @return void */
    public function __construct(){
        parent::__construct();
    }

    /* Execute the console command. @return int */
    public function handle(){
        $acService = new ActiveCampaignService();

        //query `carts` table for shipped orders (updated 30-61 minutes ago)
        $carts = Cart::where([
            ['completed', '=', 2],  // CART_COMPLETE
            ['user_id', '!=', 0],
            ['status', '=', 1],     // Shipped
            ['updated_at', '>', Carbon::now()->subMinutes(61)->toDateTimeString()],
            ['updated_at', '<', Carbon::now()->subMinutes(29)->toDateTimeString()]
        ])
        ->orderBy('created_at', 'DESC')->get();

        if ($carts->isEmpty()) {
            $this->info('No shipped carts found');
            return 0;
        }

        $processedCount = 0;
        foreach ($carts as $cart) {
            try {
                $user = User::find($cart->user_id);
                if (!$user) {
                    continue;
                }

                // Update tracking information
                if ($cart->tracking) {
                    $acService->updateTracking($user->email, $cart->tracking);
                }

                // Add shipped tag to trigger automation in AC
                $result = $acService->addTagByEmail(
                    $user->email,
                    ActiveCampaignService::TAG_CART_SHIPPED
                );

                if ($result) {
                    $processedCount++;
                    $this->info("Tagged shipped cart: {$user->email}");
                }
            } catch (\Exception $e) {
                $this->error("Failed to process cart {$cart->id}: {$e->getMessage()}");
            }
        }

        $this->info("Processed {$processedCount} shipped carts");
        return 0;
    }
}

