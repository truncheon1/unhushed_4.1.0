<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    const CART_OPEN = 0;
    const CART_ORDERED = 1;
    const CART_COMPLETE = 2;
    const CART_CANCELED = 3;

    protected $fillable = [
        'payment_id', //Paypal for legacy and Stripe for updated payments system
        'user_id', //user who owns the cart
        'completed', //0=open, 1=ordered, 2=finished, 3=canceled
        'tax', //tax percentage at time of order
        'shipping', //shipping cost at time of order
        'total', //order total at time of order
        'status', //fullfilment status for legacy orders
        'shipped', //shipping date for legacy orders 
        'tracking', //tracking number for legacy orders
        'completed_at', //timestamp when the cart was completed
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'shipping' => 'decimal:2',
        'tax' => 'decimal:2',
        'completed_at' => 'datetime',
    ];

    public function items() {
        return $this->hasMany('App\Models\CartItem', 'cart_id');
    }

    public function only_digital(){
        $items = CartItem::where('cart_id', $this->id)->get();

        foreach($items as $item){
            $p = Products::find($item->item_id);
            // Check if product has physical shipping
            if(!$p)
                return false;
            // If any variant requires shipping, it's not digital-only
            if($item->productVar && $item->productVar->ship_type !== 0)
                return false;
        }
        return true;
    }
}
