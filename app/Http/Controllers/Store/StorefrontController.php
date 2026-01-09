<?php

namespace App\Http\Controllers\Store;
use App\Http\Controllers\Controller;
use App\Models\Products;
use App\Models\ProductDetails;
use App\Models\ProductRatings;
use App\Models\ProductTags;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class StorefrontController extends Controller
{
    /**
     * Map product category integer to section info
     */
    private function getCategoryInfo($category)
    {
        $categories = [
            0 => ['slug' => '/store', 'name' => 'Store', 'section' => 'store'],
            1 => ['slug' => '/store/curricula', 'name' => 'Activities & Curricula', 'section' => 'activities'],
            2 => ['slug' => '/store/curricula', 'name' => 'Activities & Curricula', 'section' => 'activities'],
            3 => ['slug' => '/store/books', 'name' => 'Books', 'section' => 'books'],
            4 => ['slug' => '/store/games', 'name' => 'Games', 'section' => 'games'],
            5 => ['slug' => '/store/swag', 'name' => 'Swag', 'section' => 'swag'],
            6 => ['slug' => '/store/tools', 'name' => 'Teaching Tools', 'section' => 'tools'],
            7 => ['slug' => '/store/trainings', 'name' => 'Trainings', 'section' => 'trainings'],
        ];
        return $categories[$category] ?? $categories[0];
    }

    //check product and subscription availability
    public function available_products(){
        // Use model scope chain for unified availability (variant-level + legacy restrictions)
        return Products::available()->pluck('id')->toArray();
    }

    //update qty in store and cart in real time
    public function calc_total_products(Request $req, $path, $id){
        $product = Products::available()->find($id);
        if(!$product){
            return response()->json(['error'=>true, 'message'=>'Unknown product']);
        }
        
        $q = max(1, intval($req->input('qty') ?? 1));
        $varId = $req->input('var_id');
        
        // Get pricing from ProductVar if var_id is provided
        if($varId){
            $variant = \App\Models\ProductVar::where('product_id', $product->id)
                ->where('var_id', $varId)
                ->first();
            if($variant){
                $unit = $variant->price;
            } else {
                return response()->json(['error'=>true, 'message'=>'Variant not found']);
            }
        } else {
            // Fallback to first available variant or product base price
            $variant = $product->vars()->where('avail', 1)->first();
            $unit = $variant ? $variant->price : $product->price;
        }
        
        return response()->json([
            'error'=>false,
            'qty'=>$q,
            'total'=>number_format($unit * $q, 2),
            'price'=>$unit
        ]);
    }
    
    public function calc_curriculum_price(Request $req, $path, $id){
        $product = Products::where('id', $id)->where('category', 1)->first();
        if(!$product){
            return response()->json(['error'=>true, 'message'=>'Curriculum product not found']);
        }
        
        $qty = max(1, intval($req->input('qty') ?? 1));
        
        // Get pricing tier based on quantity from curriculum_prices
        $pricing = \App\Models\CurriculumPrice::where('product_id', $product->id)
            ->where('min_users', '<=', $qty)
            ->where('max_users', '>=', $qty)
            ->first();
        
        if(!$pricing){
            // If no exact match, get the highest tier available
            $pricing = \App\Models\CurriculumPrice::where('product_id', $product->id)
                ->orderBy('max_users', 'DESC')
                ->first();
        }
        
        if(!$pricing){
            return response()->json(['error'=>true, 'message'=>'No pricing information available']);
        }
        
        return response()->json([
            'error'=>false,
            'qty'=>$qty,
            'discount_price'=>$pricing->discount_price,
            'recurring_price'=>$pricing->recurring_price,
            'standard_price'=>$pricing->standard_price,
            'package_caption'=>$pricing->package_caption,
            'total'=>number_format($pricing->discount_price * $qty, 2)
        ]);
    }
    public function calc_total_subscriptions(Request $req, $path, $id){
        // Curricula products now use curriculum_prices table instead of packages
        $product = Products::where('id', $id)->where('category', 1)->first();
        if(!$product){
            return response()->json(['success' => false, 'message' => 'Curriculum product not found']);
        }
        
        $qty = max(1, intval($req->input('qty') ?? 1));
        
        // Get pricing tier based on quantity
        $pricing = \App\Models\CurriculumPrice::where('product_id', $product->id)
            ->where('min_users', '<=', $qty)
            ->where('max_users', '>=', $qty)
            ->first();
        
        if(!$pricing){
            // If no exact match, get the highest tier available
            $pricing = \App\Models\CurriculumPrice::where('product_id', $product->id)
                ->orderBy('max_users', 'DESC')
                ->first();
        }
        
        if(!$pricing){
            return response()->json(['success' => false, 'message' => 'No pricing information available']);
        }
        
        return response()->json([
            'success' => true,
            'option' => [
                'discount_price' => $pricing->discount_price,
                'recurring_price' => $pricing->recurring_price,
                'standard_price' => $pricing->standard_price,
                'package_caption' => $pricing->package_caption,
                'min_users' => $pricing->min_users,
                'max_users' => $pricing->max_users,
            ]
        ]);
    }

    public function get_product($path, $slug){
        // Retrieve product via slug ensuring at least one available variant + legacy availability
        $product = Products::where('slug', $slug)
            ->available()
            ->with(['images' => function($q){ $q->orderBy('sort','ASC'); }])
            ->withAvailableVarsOnly()
            ->firstOrFail();
        $images = $product->images; // already eager loaded
        
        // Load all available variants for all product types (avail=1, qty>0)
        $vars = $product->vars()
            ->where('avail', 1)
            ->where('qty', '>', 0)
            ->orderBy('sort', 'ASC')
            ->orderBy('var_id', 'ASC')
            ->get();
        
        /** GET PRODUCT DETAILS */
        // Get product details if they exist (null if not assigned)
        $details = ProductDetails::where('product_id', $product->id)->get();
        if($details->isEmpty()) {
            $details = null;
        }
        
        /** GET REVIEWS */
        $reviews = ProductRatings::where('product_id', $product->id)
        ->where('status', 1)
        ->with('user')
        ->orderBy('rating', 'DESC')
        ->get();
        
        // Get category info for breadcrumbs
        $categoryInfo = $this->getCategoryInfo($product->category);
        
        // Calculate pricing variables once for use across all blade partials
        if($product->category == 1) {
            // Curriculum products - check curriculum_prices table
            $hasPricing = $product->curriculumPrices()->exists();
            $displayPrice = $hasPricing ? $product->curriculumPrices()->orderBy('min_users', 'ASC')->first()->discount_price : 0;
        } else {
            // All other products - check product_vars table
            $hasPricing = $product->variants()->where('avail', 1)->where('price', '>', 0)->exists();
            $displayPrice = $hasPricing ? $product->variants()->where('avail', 1)->orderBy('price', 'ASC')->first()->price : 0;
        }
        
        // Get option values and variant assignments for products with options
        $variantIds = $vars->pluck('var_id');
        
        // Get variant assignments first
        $variantAssignments = [];
        $usedValueIds = []; // Track which option values are actually used by available variants
        if($variantIds->isNotEmpty()){
            $rows = DB::table('product_options_vars_junction')
                ->whereIn('var_id', $variantIds)
                ->get(['var_id','value_id']);
            foreach($rows as $r){
                $variantAssignments[$r->var_id] = $variantAssignments[$r->var_id] ?? [];
                $variantAssignments[$r->var_id][] = $r->value_id;
                $usedValueIds[] = $r->value_id; // Track used values
            }
        }
        $usedValueIds = array_unique($usedValueIds);
        
        // Only get option values that are actually assigned to available variants
        $optionValues = collect();
        if(!empty($usedValueIds)){
            $optionValues = DB::table('product_options_values as v')
                ->join('product_options as o','o.option_id','=','v.options_id')
                ->whereIn('v.value_id', $usedValueIds) // Only values used by variants
                ->select('v.value_id','v.options_id','v.name','o.name as option_name')
                ->orderBy('o.name')
                ->orderBy('v.name')
                ->get();
        }
        
        //return item
        return view('store.product.product')
        ->with('images', $images)
        ->with('product', $product)
        ->with('descriptions', $product->descriptions()->orderBy('sort', 'ASC')->get())
        ->with('details', $details)
        ->with('vars', $vars)
        ->with('reviews', $reviews)
        ->with('categoryInfo', $categoryInfo)
        ->with('optionValues', $optionValues)
        ->with('variantAssignments', $variantAssignments)
        ->with('section', $categoryInfo['section'])
        ->with('hasPricing', $hasPricing)
        ->with('displayPrice', $displayPrice)
        ->with('filter', [])
        ->with('hideFilters', true)
        ->with('path', get_path($path));
    }

    //Store main view frontend
    //we will need to add an availability check here later WHERE avail=1 AND id NOT IN (SELECT product_id FROM product_availabilities WHERE(`type`='quantity' AND quantity=0) OR (`type`='time' AND (NOW() NOT BETWEEN `start` AND `stop`)))
    public function store($path = 'educators'){
        $products = Products::available()
        ->withAvailableVarsOnly()
        ->with(['images' => function($query) {
            $query->orderBy('sort', 'ASC')->limit(1);
        }])
        ->orderBy('category', 'ASC')
        ->orderBy('sort', 'ASC')
        ->get();
        return view('store.store')
        ->with('products', $products)
        ->with('section', 'store')
        ->with('path', get_path($path));
    }

    //Activities & Curricula
    public function curricula(Request $req, $path = 'educators'){
        $filter = $req->input('s') ?? [];
        $query = Products::available()
            ->withAvailableVarsOnly()
            ->with(['images' => function($query) { $query->orderBy('sort','ASC')->limit(1); }])
            ->whereIn('category',[1,2]);
        if(count($filter)){
            $product_ids = ProductTags::whereIn('tag_id', $filter)->pluck('product_id');
            $query->whereIn('id', $product_ids);
        }
        $activities = $query->orderBy('category', 'ASC')->orderBy('sort','ASC')->get();
        return view('store.curricula')
        ->with('activities', $activities)
        ->with('section', 'activities')
        ->with('path', get_path($path))
        ->with('filter', $filter);
    }
    //Books
    public function books(Request $req, $path = 'educators'){
        $filter = $req->input('s') ?? [];
        $query = Products::available()
            ->withAvailableVarsOnly()
            ->with(['images' => function($query){ $query->orderBy('sort','ASC')->limit(1); }])
            ->where('category',3);
        if(count($filter)){
            $product_ids = ProductTags::whereIn('tag_id', $filter)->pluck('product_id');
            $query->whereIn('id', $product_ids);
        }
        $books = $query->orderBy('category', 'ASC')->orderBy('sort','ASC')->get();
        return view('store.books')
        ->with('books', $books)
        ->with('section', 'books')
        ->with('path', get_path($path))
        ->with('filter', $filter);
    }

    //Games
    public function games(Request $req, $path = 'educators'){
        $filter = $req->input('s') ?? [];
        $query = Products::available()
            ->withAvailableVarsOnly()
            ->with(['images' => function($query){ $query->orderBy('sort','ASC')->limit(1); }])
            ->where('category',4);
        if(count($filter)){
            $product_ids = ProductTags::whereIn('tag_id', $filter)->pluck('product_id');
            $query->whereIn('id', $product_ids);
        }
        $games = $query->orderBy('category', 'ASC')->orderBy('sort','ASC')->get();
        return view('store.games')
        ->with('games', $games)
        ->with('section', 'games')
        ->with('path', get_path($path))
        ->with('filter', $filter);
    }

    //Donate
    public function donate($path = 'educators'){
        return view('store.donate')
        ->with('section', 'swag')
        ->with('path', get_path($path));
    }
    //Swag
    public function swag(Request $req, $path = 'educators'){
        $filter = $req->input('s') ?? [];
        $query = Products::available()
            ->withAvailableVarsOnly()
            ->with(['images' => function($query){ $query->orderBy('sort','ASC')->limit(1); }])
            ->where('category',5);
        if(count($filter)){
            $product_ids = ProductTags::whereIn('tag_id', $filter)->pluck('product_id');
            $query->whereIn('id', $product_ids);
        }
        $swags = $query->orderBy('category', 'ASC')->orderBy('sort','ASC')->get();
        return view('store.swag')
        ->with('swags', $swags)
        ->with('section', 'swag')
        ->with('path', get_path($path))
        ->with('filter', $filter);
    }
    public function get_swag_size(Request $request){
        // Swag sizes are now ProductVar entries
        if(!$request->input('id') || !$variant = \App\Models\ProductVar::find($request->input('id'))){
            return response()->json(['error'=>true, 'message'=>'Variant not found!']);
        }
        return response()->json(['error'=>false, 'data'=>$variant]);
    }
    //Teaching Tools
    public function tools(Request $req, $path = 'educators'){
        $filter = $req->input('s') ?? [];
        $query = Products::available()
            ->withAvailableVarsOnly()
            ->with(['images' => function($query){ $query->orderBy('sort','ASC')->limit(1); }])
            ->where('category',6);
        if(count($filter)){
            $product_ids = ProductTags::whereIn('tag_id', $filter)->pluck('product_id');
            $query->whereIn('id', $product_ids);
        }
        $tools = $query->orderBy('category', 'ASC')->orderBy('sort','ASC')->get();
        return view('store.tools')
        ->with('tools', $tools)
        ->with('section', 'tools')
        ->with('path', get_path($path))
        ->with('filter', $filter);
    }
    //Trainings
    public function trainings(Request $req, $path = 'educators'){
        $filter = $req->input('s') ?? [];
        $query = Products::available()
            ->withAvailableVarsOnly()
            ->with(['images' => function($query){ $query->orderBy('sort','ASC')->limit(1); }])
            ->where('category',7);
        if(count($filter)){
            $product_ids = ProductTags::whereIn('tag_id', $filter)->pluck('product_id');
            $query->whereIn('id', $product_ids);
        }
        $trainings = $query->orderBy('category', 'ASC')->orderBy('sort','ASC')->get();
        return view('store.trainings')
        ->with('trainings', $trainings)
        ->with('section', 'trainings')
        ->with('path', get_path($path))
        ->with('filter', $filter);
    }

    //Professionals Welcome page with handbooks
    public function professionals(Request $req){
        $filter = $req->input('s') ?? [];
        $query = Products::available()
            ->withAvailableVarsOnly()
            ->where('category',3)
            ->where('name', 'LIKE', '%' . 'A Handbook for' . '%')
            ->with(['images' => function($q){ $q->orderBy('sort','ASC')->limit(1); }]);
        if(count($filter)){
            $product_ids = ProductTags::whereIn('tag_id', $filter)->pluck('product_id');
            $query->whereIn('id', $product_ids);
        }
        $books = $query->orderBy('sort','ASC')->get();
        return view('professionals')
        ->with('books', $books)
        ->with('section', 'books')
        ->with('path', 'professionals')
        ->with('filter', $filter);
    }

    public function mental_health($path = 'professionals'){
        $product = Products::where('name','A Handbook for Mental Health Practitioners')
            ->available()
            ->with(['images' => function($q){ $q->orderBy('sort','ASC'); }])
            ->withAvailableVarsOnly()
            ->firstOrFail();
        $images = $product->images;
        if($product->category != 7){
            $details = ProductDetails::where('product_id', $product->id)->get();
            $option = null;
            $swag = null;
        } else {
            $details = null; $option = null; $swag = null;
        }
        //add session to client
        session(['hmhp'=> true]);
        return view('professionals.mental-health')
        ->with('images', $images)
        ->with('product', $product)
        ->with('details', $details)
        ->with('path', get_path($path));
    }

    public function nursing($path = 'professionals'){
        $product = Products::where('name','A Handbook for Nursing Professionals')
            ->available()
            ->with(['images' => function($q){ $q->orderBy('sort','ASC'); }])
            ->withAvailableVarsOnly()
            ->firstOrFail();
        $images = $product->images;
        if($product->category != 7){
            $details = ProductDetails::where('product_id', $product->id)->get();
            $option = null; $swag = null;
        } else { $details = null; $option = null; $swag = null; }
        return view('professionals.nursing')
        ->with('images', $images)
        ->with('product', $product)
        ->with('details', $details)
        ->with('path', get_path($path));
    }

    public function child_welfare($path = 'professionals'){
        $product = Products::where('name','A Handbook for Child Welfare Providers')
            ->available()
            ->with(['images' => function($q){ $q->orderBy('sort','ASC'); }])
            ->withAvailableVarsOnly()
            ->firstOrFail();
        $images = $product->images;
        if($product->category != 7){
            $details = ProductDetails::where('product_id', $product->id)->get();
            $option = null; $swag = null;
        } else { $details = null; $option = null; $swag = null; }
        return view('professionals.child-welfare')
        ->with('images', $images)
        ->with('product', $product)
        ->with('details', $details)
        ->with('path', get_path($path));
    }

    public function physicians($path = 'professionals'){
        $product = Products::where('name','A Handbook for Primary Care Providers')
            ->available()
            ->with(['images' => function($q){ $q->orderBy('sort','ASC'); }])
            ->withAvailableVarsOnly()
            ->firstOrFail();
        $images = $product->images;
        if($product->category != 7){
            $details = ProductDetails::where('product_id', $product->id)->get();
            $option = null; $swag = null;
        } else { $details = null; $option = null; $swag = null; }
        return view('professionals.physicians')
        ->with('images', $images)
        ->with('product', $product)
        ->with('details', $details)
        ->with('path', get_path($path));
    }

}
