<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\PurchaseItem;
use App\Models\Orders;
use App\Models\OrderItems;
use App\Models\Products;
use App\Models\ProductVar;
use App\Models\Subscriptions;

class InvoiceController extends Controller
{
    public $section = 'my-order';

    public function myOrder(){
        $details = [];

        $items = CartItems::where('cart_id', auth()->user()->id)->orderBy('created_at', 'DESC')->get();
        $products = [];
        $status = [
            Cart::CART_OPEN =>'Added',
            Cart::CART_ORDERED => 'Checkout',
            Cart::CART_COMPLETE => 'Completed',
            Cart::CART_CANCELED => 'Canceled'
        ];


        foreach($orders as $order){
            $pp_id = $order->pp_id;
            $items = $order->items; //purchase_items table in the database
            $total = $order->total;
            $tracking = $order->tracking;

            foreach($items as $item){
                // Use product relationship to get category instead of item_type
                $product = $item->product; // PurchaseItem has product() relationship
                if($product){
                    // Handle based on product category
                    if($product->category == 0 || $product->category == 1 || $product->category == 2 || $product->category == 3 || $product->category == 4 || $product->category == 6 || $product->category == 8){
                        // Generic products, curriculum, activities, books, games, toolkits
                        $product = Products::find($item->product_id);
                    }
                    if($product->category == 7){
                        // Swag items (category=7) use ProductVar
                        $variant = ProductVar::find($item->var_id);
                        $swagItem = Products::find($item->product_id);
                    }
                }
            }

            $products[] = [
                'order'    =>$order,
                'pp_id'    =>$pp_id,
                'total'    =>$total,
                'status'   =>$status[$order->completed],
                'tracking' =>$tracking,
            ];
        }

       return view('backend.finance.my-order', compact('details'), compact('products'))->with('section', $this->section);
    }
}
