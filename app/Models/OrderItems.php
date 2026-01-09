<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItems extends Model
{
    use HasFactory;

    const ITEM_TYPE_SUBSCRIPTION = 0;
    const ITEM_TYPE_PRODUCT = 1;
    const ITEM_TYPE_GIFT = 2;
    const ITEM_TYPE_DONATION = 3;
    const ITEM_TYPE_TRAINING = 4;
    /* Adi, does it make sense to have these match the product types in the product collection? This seems like it could get very messy very quickly.
    const TYPES = [
        'activityD'=>1,
        'activityP'=>2,
        'activity'=>2,
        'bookD'=>3,
        'bookP'=>4,
        'book'=>4,
        'curriculum'=>5,
        'game'=>6,
        'swag'=>7,
        'tool'=>8,
        'training'=>9
    ];
    */
}
