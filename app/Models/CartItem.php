<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    const TYPE_PRODUCT = 0;
    const TYPE_ACTIVITYD = 1;
    const TYPE_ACTIVITYP = 2;
    const TYPE_BOOKD = 3;
    const TYPE_BOOKP = 4;
    const TYPE_CURRICULUM = 5; //This item type goes through the order system instead of the cart system
    const TYPE_GAME = 6;
    const TYPE_SWAG = 7;
    const TYPE_TOOL = 8;
    const TYPE_TRAINING = 9;

    public function cart() {
        return $this->belongsTo('App\Models\Cart');
    }

    public function productVar() {
        return $this->belongsTo('App\Models\ProductVar', 'var_id', 'var_id');
    }

    public function product() {
        return $this->belongsTo('App\Models\Products', 'item_id', 'id');
    }
}
