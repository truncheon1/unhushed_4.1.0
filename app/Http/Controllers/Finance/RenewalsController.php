<?php

namespace App\Http\Controllers\Finance;
use App\Http\Controllers\Controller;
use App\Models\Orders;
use App\Models\OrderItems;
use App\Models\PayPalProductAssoc;
use App\Models\Products;
use App\Models\Subscriptions;
use Carbon\Carbon;

class RenewalsController extends Controller
{
    public function renewals($path = 'educators'){
        $details = [];
        $date = Carbon::now();
        $date2 = Carbon::now()->addDays(30);
        $subscriptions = Subscriptions::where('exp_date', '>=', $date)
            ->where('exp_date', '<=', $date2)
            ->orderBy('exp_date', 'ASC')
            ->get();
        $total = $subscriptions->sum('due');
        foreach($subscriptions as $s){
            $id = $s->id;
            $details[] = [
                'id'         => $id,
                'name'       => $s->username()? $s->username()->name : '',
                'org'        => $s->orgname()? $s->orgname()->name : '',
                'packname'   => $s->packname()? $s->packname()->name : '',
                'order'      => $s->order_id,
                'exp_date'   => Carbon::parse($s->exp_date)->format('m-d-Y'),
                'due'        => $s->due,
                'created'    => $s->created_at->format('m-d-Y'),
                'notes'      => $s->notes,
            ];
        }
        return view('backend.finance.fulfillment.renewals', compact('details'))
        ->with('total', $total)
        ->with('section', 'backend')
        ->with('path', get_path($path));
    }

    public function subscriptons_trainings($path = 'educators'){
        $details = [];
        //find subscription
        $subs = Subscriptions::where('active', '>', '1')->orderBy('created_at', 'DESC')->get();
        foreach($subs as $s){
            $id = $s->id;
            //product info
            $p_id = $s->product_id;
            $product = Products::find($p_id);
            if(!$product){
                $p_info = PayPalProductAssoc::where('paypal_product_id', $p_id)->first();
                if($p_info){
                    $product = Products::find($p_info->item_id);
                }
            }
            if(!$product){
                $product    = 'Product not found!';
                $packname   = '';
                $s_status   = '';
            }elseif($product->category === 7){
                $packname = $product->name;
                $s_status = 'training';
            }elseif($product->category === 1){
                $item = Products::where('ref_id', $product->ref_id)->first();
                if(isset($item->name)){
                    $packname  = $item->name;
                }else{
                    $packname  = 'Training name not found!';
                }
                if($s->active == 2){
                    $s_status = 'active';
                }
                if($s->active == 3){
                    $s_status = 'canceled';
                }
                if($s->active == 4){
                    $s_status = 'reviewing';
                }
            }
            //find order total
            $total = Orders::where('id', $s->order_id)->value('total');
            //find items and qty
            $items = OrderItems::where('order_id', $s->order_id)->get();
            $oi = [];
            foreach($items as $item){
                if($item->item_type == 0){
                    $p = Products::where('ref_id', $item->item_id)->first();
                    if(!$p)
                        continue;
                    $oi[] = [
                        "name"=>$p->name,
                        "qty"=>$item->qty,
                        "cost"=>$item->price,
                    ];
                }else{
                    $t = Products::where('ref_id', $item->item_id)->first();
                    if(!$t)
                        continue;
                    $oi[] = [
                        "name"=>$t->name,
                        "qty"=>$item->qty,
                        "cost"=>$item->price,
                    ];
                }
            }
        //send info to blade
            $details[] = [
                'id'        => $id,
                'user'      => $s->user_id,
                'order'     => $s->order_id,
                'ref_id'    => $s->subscription_id,
                'status'    => $s_status,
                'exp_date'  => $s->exp_date ? Carbon::parse($s->exp_date)->format('m/d/Y') : '',
                'due'       => $s->due ? number_format($s->due, 2): '', 
                'created'   => $s->created_at->format('m/d/Y'),
                'notes'     => $s->notes,
                'name'      => $s->username()? $s->username()->name : '',
                'org'       => $s->orgname()? $s->orgname()->name : '',
                'org_id'    => $s->orgname()? $s->orgname()->id : '',
                'product_id' =>$s->product_id,
                'packname'  => $packname,
                'total'     => $total ? number_format($total, 2): '',
                'items'     => $oi
            ];
        }
        //dd($details);
        return view('backend.finance.fulfillment.subscriptons-trainings', compact('details'))
        ->with('section', 'backend')
        ->with('path', get_path($path));
    }
}
