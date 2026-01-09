<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \App\Models\OrderItems;
use \App\Models\UserAddress;

class Orders extends Model
{
    use HasFactory;

    const STATUS_INITIATED = 0;
    const STATUS_CANCELED = 1;
    const STATUS_PAYMENT_SUCCES = 2;
    const STATUS_SENT = 3;

    public function items(){
        return $this->hasMany(OrderItems::class, 'order_id')->get();
    }
}
