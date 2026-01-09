<?php
namespace App\Http\Controllers\Finance;
use App\Models\DiscountRestriction;
use App\Models\Products;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Discounts;

class DiscountController extends Controller
{
    //Discount CRUD
    public function index($path = 'educators'){
        return view('backend.finance.discounts')
        ->with('section', 'backend')
        ->with('products', Products::get())
        ->with('path', get_path($path));
    }

    public function create(Request $req){
        //Setup error message
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
        $discount->restricted = (int) $req->input('restricted');
        $discount->save();
        if($discount->restricted == 1){
            $products = $req->input('products') ?? [];
            foreach($products as $pid){
                $dp = new DiscountRestriction();
                $product = Products::find($pid);
                $dp->discount_id = $discount->id;
                $dp->product_id = $product->id;
                $dp->product_type = $product->category;
                $dp->save();
            }
        }
        return response()->json(['error'=>false]);
    }

    public function view($path, $id){
        $discount = Discounts::find($id);
        if(!$discount){
            return response()->json(['success'=>false, 'messaage'=>'Invalid ID']);
        }
        $discountRestricted = DiscountRestriction::where('discount_id',$id)->get();
        return response()->json(['success'=>true, 'discount'=>$discount, 'restricted'=>$discountRestricted]);
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
        $discount->restricted = (int) $req->input('restricted');
        $discount->save();
        if($discount->restricted == 1){
            $products = $req->input('products') ?? [];
            $existingProducts = DiscountRestriction::where('discount_id', $discount->id)->pluck('product_id');
            foreach($products as $pid){
                $product = Products::find($pid);
                DiscountRestriction::updateOrCreate(
                    [
                        'discount_id'   => $discount->id,
                        'product_id'    => $product->id,
                        'product_type'  => $product->category
                    ]
                );
            }
            DiscountRestriction::where('discount_id', $discount->id)
                               ->whereNotIn('product_id', $products)
                                ->delete();
        }
        return response()->json(['success'=>true, 'discount'=>$discount]);
    }

    public function delete($path = 'educators', $id){
        Discounts::destroy($id);
        return response()->json(['success'=>true]);
    }
}
