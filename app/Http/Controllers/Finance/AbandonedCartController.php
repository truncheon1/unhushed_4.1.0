<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Cart;


class AbandonedCartController extends Controller
{
    public function abandonedCarts(){
        $date = new \DateTime();
        $date->sub(new \DateInterval('P1D'));

        //query `carts` table for abandoned carts
        $carts = Cart::where([
            ['completed', '!=', Cart::CART_COMPLETE],
            ['user_id', '!=', 0],
            ['updated_at', '>', \Carbon\Carbon::now()->subHours(36)->toDateTimeString()],
            ['updated_at', '<', \Carbon\Carbon::now()->subHours(12)->toDateTimeString()]
            ])
            ->orderBy('created_at', 'DESC')->get();

            //find the user_id associated with the cart
            $a_cart = [];
            foreach($carts as $a_cart){
                $id = $a_cart->id;
                $user_id = $a_cart->user_id;
                $users[] = [
                    'id'       =>$id,
                    'user_id'    =>$user_id,
                ];
            //query the `user` table for their email address
            $email = User::where('id', $user_id)->value('email');

            dd($id, $user_id, $email);
        }
    }
}
