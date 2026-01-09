<?php
namespace App\Console\Commands;
use Illuminate\Console\Command;
class ProductCheck extends Command
{
    /* The name and signature of the console command. @var string */
    protected $signature = 'products:check';

    /* The console command description.  @var string */
    protected $description = 'Check for expiration date on products';

    /* Create a new command instance. @return void */
    public function __construct(){
        parent::__construct();
    }

    /* Execute the console command. @return int */
    public function handle(){
        $products = \App\Models\Products::all();
        //since we need to switch availability on or off, we need to check all the products
    }
}
