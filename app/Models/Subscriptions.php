<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Organizations;
use App\Models\Products;

class Subscriptions extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_id',
        'product_id',
        'pp_product_id',
        'subscription_id',
        'active',
        'exp_date',
        'due',
        'notes',
    ];

    // Active status constants
    const INACTIVE = 0;
    const ACTIVE = 1;

    //refactor during type update
    const SUBSCRIPTION_INTENT = 1;
    const SUBSCRIPTION_ACTIVE = 2;
    const SUBSCRIPTION_EXPIRED = 3;
    const SUBSCRIPTION_REVIEWING = 4;


    public function username(){
        return User::find($this->user_id);
    }

    public function orgname(){
        $user = $this->username();
        if (is_null($user)){
            'user error';
        }else{
        return Organizations::find($user->org_id);
        }
    }

    public function packname(){
        return Products::find($this->product_id);
    }
}
