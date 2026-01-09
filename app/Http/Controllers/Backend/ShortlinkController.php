<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Shortlinks;

class ShortlinkController extends Controller
{
    public function index($path = 'educators'){
        return view('backend.links.short-links')
        ->with('section', 'backend')
        ->with('path', get_path($path));
    }


    //Copy Pasted code from discounts
    public function create(Request $req){
        $error = true;
        $code =  strtoupper($req->input('code'));
        $discount_type = (int) $req->input('discount_type');
        $value = floatval($req->input('value'));
        $available = intval($req->input('available'));
        $until = $req->input('until');
        $is_code = Discounts::where('code', 'LIKE', $code)->exists();
        if($is_code)
            return response()->json(['error'=>$error, 'message'=>'Discount Code already exists.']);

        if($value < 1)
            return response()->json(['error'=>$error, 'message'=>'Value must be at least 1.']);

        if($discount_type == Discounts::TYPE_PERCENTAGE && $value > 99){
            return response()->json(['error'=>$error, 'message'=>'Available in percentage cannot be greater than 99.']);
        }
        //all good save the code
        $discount = new Discounts();
        $discount->code = trim($code);
        $discount->discount_type = $discount_type;
        $discount->value = $value;
        $discount->expire_date = $until;
        $discount->available = $available;
        $discount->save();
        return response()->json(['error'=>false]);
    }

    public function view($id){
        $discount = Discounts::find($id);
        if(!$discount){
            return response()->json(['success'=>false, 'messaage'=>'Invalid ID']);
        }
        return response()->json(['success'=>true, 'discount'=>$discount]);
    }

    public function update(Request $req){
        $error = true;
        $code =  strtoupper($req->input('code'));
        $discount_type = (int) $req->input('discount_type') ;
        $value = floatval($req->input('value'));
        $available = intval($req->input('available'));
        $until = $req->input('until');
        if(!$req->input('id') || !$discount = Discounts::find($req->input('id'))){
            return response()->json(['success'=>false, 'messaage'=>'Invalid ID']);
        }
        if($value < 1)
            return response()->json(['error'=>$error, 'message'=>'Value must be at least 1.']);

        $is_code = Discounts::where('code', 'LIKE', $code)->where('id', '<>', $discount->id)->exists();
        if($is_code)
            return response()->json(['error'=>$error, 'message'=>'Discount Code already exists.']);
        if($discount_type == Discounts::TYPE_PERCENTAGE && $value > 99){
            return response()->json(['error'=>$error, 'message'=>'Available in percentage cannot be greater than 99.']);
        }
        $discount->code = trim($code);
        $discount->discount_type = $discount_type;
        $discount->value = $value;
        $discount->expire_date = $until;
        $discount->available = $available;
        $discount->save();
        return response()->json(['success'=>true, 'discount'=>$discount]);
    }

    public function delete($path = 'educators', $id){
        Discounts::destroy($id);
        return response()->json(['success'=>true]);
    }
}
