<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartDiscountTracking extends Model
{
    use HasFactory;

    const TYPE_CART = 1;
    const TYPE_ORDER = 2;
    public function cart() {
        return $this->belongsTo('App\Models\Discounts');
    }
}
