<?php

namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CurriculumDocument;
use App\Models\CurriculumPrice;
use App\Models\CurriculumSessions;
use App\Models\CurriculumUnits;
use App\Models\ProductFiles;
use App\Models\ProductAssignments;
use App\Models\Products;
use App\Models\ProductVar;
use App\Models\PurchaseItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class PackagesController extends Controller
{
///Package delivery///
    //BACKEND CURRICULA MAIN PAGE
    public function backend_catalog($path = 'educators'){
        $authId = \Illuminate\Support\Facades\Auth::id() ?? 0;

        ///curricula delivery///
        $package = ProductAssignments::where('user_id', $authId)
        ->where('active', 1)
        // Curriculum assignments now stored with category = 1 (was type ITEM_TYPE_SUBSCRIPTION)
        ->where('category', 1)
        ->get();
        $ids = [];
        foreach ($package as $p){
            $ids[] = $p->product_id;
        }
        $packages = [];
        if (count($ids)){
            // Eager load only the primary image (sort = 1) for each curriculum product
            // Load all images so accessor can gracefully fallback if no sort=1 exists
            $packages = Products::with('images')
                ->whereIn('id', $ids)
                ->where('category', 1)
                ->orderBy('sort', 'ASC')
                ->get();
        }

        // Free curriculum visibility governed by helper & policy flag
        $freeProduct = ProductAssignments::free();
        $showFree = $freeProduct && ProductAssignments::userHasCurriculum($authId, $freeProduct->id);

        ///activities & courses delivery - optimized with single cart query///
        // Fetch all purchased product IDs from completed carts once
        $purchasedProductIds = \Illuminate\Support\Facades\DB::table('purchase_items')
            ->join('purchase_carts', 'purchase_items.cart_id', '=', 'purchase_carts.id')
            ->where('purchase_carts.user_id', $authId)
            ->where('purchase_carts.completed', Cart::CART_COMPLETE)
            ->whereNotNull('purchase_items.product_id')
            ->distinct()
            ->pluck('purchase_items.product_id')
            ->toArray();

        // Fetch all purchased products (categories 2 or 4) with images eager-loaded
        $purchasedProducts = collect();
        if (!empty($purchasedProductIds)) {
            $purchasedProducts = Products::with('images')
                ->whereIn('id', $purchasedProductIds)
                ->whereIn('category', [2,4]) // activities (2/4) + courses (subset of 2)
                ->get();
        }

        // Courses: category 2 AND name contains 'UN|HUSHED at Home'
        $courses = $purchasedProducts->filter(function($p){
            return (int)$p->category === 2 && stripos($p->name, 'UN|HUSHED at Home') !== false;
        })->values();

        // Activities: category 2 or 4 and NOT a course
        $activities = $purchasedProducts->filter(function($p) use ($courses){
            return in_array((int)$p->category, [2,4]) && !$courses->contains('id', $p->id);
        })->values();

        return view('backend.content-curricula.index')
        ->with('packages', $package)
        ->with('subscriptions', $packages)
        ->with('activities', $activities)
        ->with('courses', $courses)
        ->with('showFree', $showFree)
        ->with('freeProduct', $freeProduct)
        ->with('section', 'curricula')
        ->with('path', get_path($path));
    }
    //K-12 CURRICULUM
        //Free
        public function free($path = 'educators'){
            // Centralized lookup via ProductAssignments helper
            $package = \App\Models\ProductAssignments::free();
            if(!$package){
                return redirect()->back()->with('error','Free curriculum product not found');
            }
            if(!\App\Models\ProductAssignments::userHasCurriculum(\Illuminate\Support\Facades\Auth::id(), $package->id)){
                return abort(403, 'You do not have access to the free curriculum resources.');
            }
            return view('backend.content-curricula.free')
            ->with('package', $package)
            ->with('units', \App\Models\ProductAssignments::unitsFor($package->id))
            ->with('section', 'curricula')
            ->with('path', get_path($path));
        }
        //ES
        public function elementary($path = 'educators'){
            // Canonical exact-name lookup via helper
            $package = \App\Models\ProductAssignments::elementary();
            if(!$package){
                return redirect()->back()->with('error','Elementary School Curriculum product not found');
            }
            if(!\App\Models\ProductAssignments::userHasCurriculum(\Illuminate\Support\Facades\Auth::id(), $package->id)){
                return abort(403, 'You do not have access to the elementary school curriculum.');
            }
            return view('backend.content-curricula.elementary')
            ->with('package', $package)
            ->with('units', \App\Models\ProductAssignments::unitsFor($package->id))
            ->with('section', 'curricula')
            ->with('path', get_path($path));
        }
        //MS
        public function middle($path = 'educators'){
            $package = \App\Models\ProductAssignments::middle();
            if(!$package){
                return redirect()->back()->with('error','Middle School Curriculum product not found');
            }
            if(!\App\Models\ProductAssignments::userHasCurriculum(\Illuminate\Support\Facades\Auth::id(), $package->id)){
                return abort(403, 'You do not have access to the middle school curriculum.');
            }
            return view('backend.content-curricula.middle')
            ->with('package', $package)
            ->with('units', \App\Models\ProductAssignments::unitsFor($package->id))
            ->with('section', 'curricula')
            ->with('path', get_path($path));
        }
        //HS
        public function high($path = 'educators'){
            $package = \App\Models\ProductAssignments::high();
            if(!$package){
                return redirect()->back()->with('error','High School Curriculum product not found');
            }
            if(!\App\Models\ProductAssignments::userHasCurriculum(\Illuminate\Support\Facades\Auth::id(), $package->id)){
                return abort(403, 'You do not have access to the high school curriculum.');
            }
            return view('backend.content-curricula.high')
            ->with('package', $package)
            ->with('units', \App\Models\ProductAssignments::unitsFor($package->id))
            ->with('section', 'curricula')
            ->with('path', get_path($path));
        }
    //UNHUSHED AT HOME
        //Bundle Page - Shows free Getting Started + purchased variants
        public function uahBundle($path = 'parents'){
            $user = auth()->user();
            
            // Get the main product (unhushed at home: ages 12-15)
            $product = Products::with('images')
                ->where('name', 'LIKE', '%at home%')
                ->where('name', 'LIKE', '%12-15%')
                ->first();
            
            if(!$product)
                abort(404);
            
            // Get the "Bundled Courses" variant for header image
            $bundledCoursesVariant = ProductVar::where('product_id', $product->id)
                ->where('name', 'LIKE', '%Bundled Courses%')
                ->first();
            
            // Load first image for bundled courses variant if it exists
            if($bundledCoursesVariant) {
                $allImages = \App\Models\ProductImages::where('product_id', $product->id)
                    ->orderBy('sort', 'asc')
                    ->get()->map(function($img) {
                        if($img->variant_ids && is_string($img->variant_ids)){
                            $img->variant_ids = json_decode($img->variant_ids, true);
                        }
                        return $img;
                    });
                    
                $bundledCoursesVariant->firstImage = $allImages->first(function($image) use ($bundledCoursesVariant) {
                    if ($image->variant_ids === null || $image->variant_ids === '') {
                        return true; // Assigned to all variants
                    }
                    return is_array($image->variant_ids) && in_array($bundledCoursesVariant->var_id, $image->variant_ids);
                });
                    
                // Load description for bundled courses variant
                $allDescriptions = \App\Models\ProductDescription::where('product_id', $product->id)
                    ->orderBy('sort', 'asc')
                    ->get()->map(function($desc) {
                        if($desc->variant_ids && is_string($desc->variant_ids)){
                            $desc->variant_ids = json_decode($desc->variant_ids, true);
                        }
                        return $desc;
                    });
                    
                $bundledCoursesVariant->description = $allDescriptions->first(function($desc) use ($bundledCoursesVariant) {
                    if ($desc->variant_ids === null || $desc->variant_ids === '') {
                        return true; // Assigned to all variants
                    }
                    return is_array($desc->variant_ids) && in_array($bundledCoursesVariant->var_id, $desc->variant_ids);
                });
            }
            
            // Always include the free "Getting Started" variant
            $freeVariant = ProductVar::where('product_id', $product->id)
                ->where('name', 'LIKE', '%Getting Started%')
                ->first();
            
            if(!$freeVariant)
                abort(404);
            
            // Get all variants the user has purchased for this product by checking purchase history
            $purchasedVariantIds = \DB::table('purchase_items')
                ->join('purchase_carts', 'purchase_items.cart_id', '=', 'purchase_carts.id')
                ->where('purchase_carts.user_id', $user->id)
                ->where('purchase_carts.completed', \App\Models\PurchaseCart::CART_COMPLETE)
                ->where('purchase_items.product_id', $product->id)
                ->whereNotNull('purchase_items.var_id')
                ->distinct()
                ->pluck('purchase_items.var_id')
                ->toArray();
            
            // Combine free variant with purchased variants (avoid duplicates)
            $accessibleVariantIds = array_unique(array_merge([$freeVariant->var_id], $purchasedVariantIds));
            
            // Load all accessible variants with their details
            $variants = ProductVar::whereIn('var_id', $accessibleVariantIds)
                ->where('product_id', $product->id)
                ->orderBy('sort', 'asc')
                ->get();
            
            // For each variant, load its files and first image
            $variantsWithFiles = $variants->map(function($variant) use ($product) {
                // Get all files for this product and manually decode variant_ids
                $allFiles = ProductFiles::where('product_id', $product->id)->get()->map(function($file) {
                    if($file->variant_ids && is_string($file->variant_ids)){
                        $file->variant_ids = json_decode($file->variant_ids, true);
                    }
                    return $file;
                });
                
                // Filter files that match this variant
                $files = $allFiles->filter(function($file) use ($variant) {
                    if ($file->variant_ids === null || $file->variant_ids === '') {
                        return true; // Assigned to all variants
                    }
                    return is_array($file->variant_ids) && in_array($variant->var_id, $file->variant_ids);
                });
                
                // Get all images for this product and manually decode variant_ids
                $allImages = \App\Models\ProductImages::where('product_id', $product->id)
                    ->orderBy('sort', 'asc')
                    ->get()->map(function($img) {
                        if($img->variant_ids && is_string($img->variant_ids)){
                            $img->variant_ids = json_decode($img->variant_ids, true);
                        }
                        return $img;
                    });
                
                // Filter images that match this variant
                $matchingImages = $allImages->filter(function($image) use ($variant) {
                    if ($image->variant_ids === null || $image->variant_ids === '') {
                        return true; // Assigned to all variants
                    }
                    return is_array($image->variant_ids) && in_array($variant->var_id, $image->variant_ids);
                });
                
                // Get all descriptions for this product and manually decode variant_ids
                $allDescriptions = \App\Models\ProductDescription::where('product_id', $product->id)
                    ->orderBy('sort', 'asc')
                    ->get()->map(function($desc) {
                        if($desc->variant_ids && is_string($desc->variant_ids)){
                            // Handle double JSON encoding
                            $decoded = json_decode($desc->variant_ids, true);
                            if(is_string($decoded)) {
                                $decoded = json_decode($decoded, true);
                            }
                            $desc->variant_ids = $decoded;
                        }
                        return $desc;
                    });
                
                // Filter descriptions that match this variant
                // First try to find a specific description for this variant
                $specificDescriptions = $allDescriptions->filter(function($desc) use ($variant) {
                    return is_array($desc->variant_ids) && in_array($variant->var_id, $desc->variant_ids);
                });
                
                // If no specific description, fall back to "all variants" description
                if($specificDescriptions->isEmpty()){
                    $matchingDescriptions = $allDescriptions->filter(function($desc) {
                        return $desc->variant_ids === null || $desc->variant_ids === '';
                    });
                } else {
                    $matchingDescriptions = $specificDescriptions;
                }
                
                $variant->files = $files;
                $variant->firstImage = $matchingImages->first();
                $variant->description = $matchingDescriptions->first();
                return $variant;
            });
            
            // Check if user has purchased any non-free variants
            $hasPurchasedVariants = count($purchasedVariantIds) > 0;
            
            return view('backend.content-curricula.uah-bundle')
                ->with('product', $product)
                ->with('bundledCoursesVariant', $bundledCoursesVariant)
                ->with('variants', $variantsWithFiles)
                ->with('hasPurchasedVariants', $hasPurchasedVariants)
                ->with('section', 'curricula')
                ->with('path', get_path($path));
        }
        
        //Free-Getting Started (legacy, redirects to bundle)
        public function start($path = 'parents'){
            return redirect()->route('curricula.uah-bundle', ['path' => get_path($path)]);
        }
        //Courses
        public function course($delivery_slug, $path = 'parents'){
            //refactor during type update
            $course = Products::with('images')
                ->where('category', 2)
                ->where('name', 'LIKE', '%at home%')
                ->first();
            if(!$course)
                abort(404);
            $blade = 'backend.content-curricula.'.$course->template;
            $files = ProductFiles::where('product_id', $course->reference_id)->get();
            return view($blade)
            ->with('course', $course)
            ->with('files', $files)
            ->with('section', 'curricula')
            ->with('path', get_path($path));
        }
    //ACTIVITIES
        public function activity($delivery_slug, $path = 'educators'){
            $activity = Products::with('images')->where('slug', $delivery_slug)->first();
            if(!$activity)
                abort(404);
            $blade = 'backend.content-curricula.'.$activity->template;
            $files = ProductFiles::where('product_id', $activity->reference_id)->get();
            return view($blade)
            ->with('activity', $activity)
            ->with('section', 'curricula')
            ->with('files', $files)
            ->with('path', get_path($path));
        }

///Package CRUD///
    public function options($path = 'educators', $id){
        $package = Products::where('category', 1)->where('id', $id)->first();
        if(!$package){
            abort(404);
        }
        return view("backend.packages.options")
        ->with('package', $package)
        ->with('section', 'backend')
        ->with('path', get_path($path));
    }

    public function options_create(Request $request, $path = 'educators', $id){
        $package = Products::with('images')->find($id);
        if(!$package){
            return response()->json(['success'=>false, 'message'=>'Package not found!']);
        }
        //Setup empty message
        $error = false;
        $message = '';
        //Check each error
        $recurring_price = $request->input('recurring');
        if(!$recurring_price || !strlen(trim($recurring_price))){
            $error = true;
            $message = 'Yearly fee is required.';
        }
        $discount_price = $request->input('discount');
        if(!$discount_price || !strlen(trim($discount_price))){
            $error = true;
            $message = 'Discount price is required.';
        }
        $standard_price = $request->input('standard');
        if(!$standard_price || !strlen(trim($standard_price))){
            $error = true;
            $message = 'Standard price is required.';
        }
        $max_users = $request->input('max_users');
        if(!$max_users || !strlen(trim($max_users))){
            $error = true;
            $message = 'Max users are required.';
        }
        $package_caption = $request->input('caption');
        if(!$package_caption || !strlen(trim($package_caption))){
            $error = true;
            $message = 'Caption is required.';
        }
        //Input error
        if($error){
            return response()->json(['error'=>$error, 'message'=>$message]);
        }
        //Save
        $option = new CurriculumPrice();
        // Link directly to curriculum product id
        $option->product_id = $package->id;
        $option->package_caption = $request->input('caption');
        $option->min_users = $request->input('min_users');
        $option->max_users = $request->input('max_users');
        $option->standard_price = $request->input('standard');
        $option->discount_price = $request->input('discount');
        $option->recurring_price = $request->input('recurring');
        $option->save();
        return response()->json(['success'=>true, 'option'=>$option]);
    }

    public function options_view($path = 'educators', $pid, $id){
        $package = Products::find($pid);
        $option = CurriculumPrice::find($id);
        if(!$package || !$option){
            return response()->json(['success'=>false, 'message'=>'Invalid ID']);
        }
        //return the option + min and max bounds it can take for users
        return response()->json(['success'=>true, 'option'=>$option]);
    }

    public function options_update(Request $request, $path = 'educators', $id){
        $package = Products::find($id);
        if(!$package)
            return response()->json(['success'=>false, 'message'=>'Invalid ID']);
        $option = CurriculumPrice::find($request->input('id'));
        $option->package_caption = $request->input('caption');
        $option->min_users = $request->input('min_users');
        $option->max_users = $request->input('max_users');
        $option->standard_price = $request->input('standard');
        $option->discount_price = $request->input('discount');
        $option->recurring_price = $request->input('recurring');
        $option->save();
        return response()->json(['success'=>true, 'option'=>$option]);
    }

    public function option_delete($path = 'educators', $id, $option_id){
        $package = Products::find($id);
        if(!$package)
            return response()->json(['success'=>false, 'message'=>'Invalid ID']);
        $option = CurriculumPrice::find($option_id);
        $option->delete();
        return response()->json(['success'=>true]);
    }

    /**
     * Return curriculum license pricing tiers for a given curriculum product.
     * GET /{path}/backend/products/{id}/license-pricing
     * Response: { error:false, product:{id,name}, prices:[...] }
     */
    public function licensePricing($path = 'educators', $id){
        try {
            \Log::info('License pricing request', ['path' => $path, 'id' => $id, 'id_type' => gettype($id)]);
            $product = Products::find($id);
            if(!$product){
                \Log::warning('License pricing requested for non-existent product', ['product_id' => $id, 'path' => $path, 'products_count' => Products::count()]);
                return response()->json(['error'=>true,'message'=>'Product not found (ID: '.$id.')']);
            }
            // Only curriculum category products should have curriculum prices
            $prices = CurriculumPrice::where('product_id', $product->id)
            ->orderBy('min_users','ASC')
            ->get()
            ->map(function($p){
                return [
                    'id' => $p->id,
                    'package_caption' => $p->package_caption,
                    'min_users' => (int)$p->min_users,
                    'max_users' => (int)$p->max_users,
                    'standard_price' => number_format($p->standard_price,2,'.',''),
                    'discount_price' => number_format($p->discount_price,2,'.',''),
                    'recurring_price' => number_format($p->recurring_price,2,'.',''),
                ];
            });
            return response()->json([
                'error' => false,
                'product' => [ 'id'=>$product->id, 'name'=>$product->name ],
                'prices' => $prices,
                'path' => get_path($path)
            ]);
        } catch (\Exception $e) {
            \Log::error('Error loading license pricing', ['product_id' => $id, 'error' => $e->getMessage()]);
            return response()->json(['error'=>true,'message'=>'Error loading license pricing: '.$e->getMessage()], 500);
        }
    }

    /** Create a new curriculum price tier */
    public function licensePricingCreate(Request $request, $path = 'educators', $id){
        $product = Products::find($id);
        if(!$product){
            return response()->json(['error'=>true,'message'=>'Invalid product ID']);
        }
        $fields = ['package_caption'=>'caption','min_users'=>'min_users','max_users'=>'max_users','standard_price'=>'standard','discount_price'=>'discount','recurring_price'=>'recurring'];
        $data = [];
        foreach($fields as $column=>$input){
            $val = $request->input($input);
            if($val === null || $val === ''){
                return response()->json(['error'=>true,'message'=>ucfirst(str_replace('_',' ',$input)).' is required.']);
            }
            $data[$column] = $val;
        }
        if((int)$data['min_users'] > (int)$data['max_users']){
            return response()->json(['error'=>true,'message'=>'Min users cannot exceed Max users.']);
        }
        $tier = new CurriculumPrice();
        $tier->product_id = $product->id;
        $tier->package_caption = $data['package_caption'];
        $tier->min_users = (int)$data['min_users'];
        $tier->max_users = (int)$data['max_users'];
        $tier->standard_price = (float)$data['standard_price'];
        $tier->discount_price = (float)$data['discount_price'];
        $tier->recurring_price = (float)$data['recurring_price'];
        $tier->save();
        return response()->json(['error'=>false,'tier'=>[ 'id'=>$tier->id, 'package_caption'=>$tier->package_caption, 'min_users'=>$tier->min_users, 'max_users'=>$tier->max_users, 'standard_price'=>number_format($tier->standard_price,2,'.',''), 'discount_price'=>number_format($tier->discount_price,2,'.',''), 'recurring_price'=>number_format($tier->recurring_price,2,'.','') ]]);
    }

    /** View a single tier */
    public function licensePricingItem($path = 'educators', $id, $tier_id){
        $product = Products::find($id);
        $tier = CurriculumPrice::find($tier_id);
        if(!$product || !$tier || $tier->product_id != $product->id){
            return response()->json(['error'=>true,'message'=>'Invalid tier ID']);
        }
        return response()->json(['error'=>false,'tier'=>[ 'id'=>$tier->id, 'package_caption'=>$tier->package_caption, 'min_users'=>$tier->min_users, 'max_users'=>$tier->max_users, 'standard_price'=>number_format($tier->standard_price,2,'.',''), 'discount_price'=>number_format($tier->discount_price,2,'.',''), 'recurring_price'=>number_format($tier->recurring_price,2,'.','') ]]);
    }

    /** Update a tier */
    public function licensePricingUpdate(Request $request, $path = 'educators', $id, $tier_id){
        $product = Products::find($id);
        $tier = CurriculumPrice::find($tier_id);
        if(!$product || !$tier || $tier->product_id != $product->id){
            return response()->json(['error'=>true,'message'=>'Invalid tier ID']);
        }
        $fields = ['package_caption'=>'caption','min_users'=>'min_users','max_users'=>'max_users','standard_price'=>'standard','discount_price'=>'discount','recurring_price'=>'recurring'];
        foreach($fields as $column=>$input){
            $val = $request->input($input);
            if($val === null || $val === ''){
                return response()->json(['error'=>true,'message'=>ucfirst(str_replace('_',' ',$input)).' is required.']);
            }
            switch($column){
                case 'min_users': case 'max_users': $tier->$column = (int)$val; break;
                case 'standard_price': case 'discount_price': case 'recurring_price': $tier->$column = (float)$val; break;
                default: $tier->$column = $val; break;
            }
        }
        if($tier->min_users > $tier->max_users){
            return response()->json(['error'=>true,'message'=>'Min users cannot exceed Max users.']);
        }
        $tier->save();
        return response()->json(['error'=>false,'tier'=>[ 'id'=>$tier->id, 'package_caption'=>$tier->package_caption, 'min_users'=>$tier->min_users, 'max_users'=>$tier->max_users, 'standard_price'=>number_format($tier->standard_price,2,'.',''), 'discount_price'=>number_format($tier->discount_price,2,'.',''), 'recurring_price'=>number_format($tier->recurring_price,2,'.','') ]]);
    }

    /** Delete a tier */
    public function licensePricingDelete($path = 'educators', $id, $tier_id){
        $product = Products::find($id);
        $tier = CurriculumPrice::find($tier_id);
        if(!$product || !$tier || $tier->product_id != $product->id){
            return response()->json(['error'=>true,'message'=>'Invalid tier ID']);
        }
        $tier->delete();
        return response()->json(['error'=>false]);
    }

    public function content($path = 'educators', $id){
        $package = Products::find($id);
        if(!$package)
            abort(404);
        $units = CurriculumUnits::where('product_id', $id)->get(['id', 'name']);
        return view('backend.packages.content')
        ->with('units', $units)
        ->with('package', $package)
        ->with('path', get_path($path));
    }
    //Sessions for a unit
    /**
     * #path - educators/$id/fetch_sessions/$unit_id
     */
    public function fetch_sessions($path, $id, $unit_id){
        $unit = CurriculumUnits::find($unit_id);
        if (!$unit){
            return response()->json([]);
        }
        $sessions = $unit->sessions();
        if (!count ($sessions)) {
            return response()->json([]);
        }
        return response()->json(
                $sessions
                ->map(fn ($s) => ['name' => $s->name, 'id' => $s->id])
                ->values()
        );
    }
    //Units
    public function add_unit(Request $req, $path, $id){
        try {
            $req->validate([
                'name' => 'required|max:190',
                'number'=>'required|max:30',
                'keywords'=>'required|max:190',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['success' => false, 'message' => $e->errors()]);
        }
        $curriculumUnit = new CurriculumUnits();
        $curriculumUnit->name = $req->input('name');
        $curriculumUnit->number = $req->input('number');
        $curriculumUnit->keywords = $req->input('keywords');
        $curriculumUnit->product_id = $id;
        $curriculumUnit->save();
        return response()->json(['success' => true]);
    }

    public function get_unit($path, $id, $unit_id){
        $unit = CurriculumUnits::find($unit_id);
        if(!$unit)
            return response()->json(['success'=>false, 'message'=>[['not found']]]);
        return response()->json(['success'=>true, 'data'=>$unit]);
    }

    public function edit_unit(Request $req, $path = 'educators', $id){
        try {
            $req->validate([
                'id'=>'required',
                'name' => 'required|max:190',
                'keywords'=>'required|max:190',
                'number'=>'required|max:30',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['success' => false, 'message' => $e->errors()]);
        }
        $unit = CurriculumUnits::find($req->input('id'));
        if(!$unit){
            return response()->json(['success'=>false, 'message'=>[['not found']]]);
        }
        $unit->name = $req->input('name');
        $unit->keywords = $req->input('keywords');
        $unit->number = $req->input('number');
        $unit->save();
        return response()->json(['success' => true]);
    }

    public function delete_unit($path, $subscription_id, $unit_id){
        $unit = CurriculumUnits::find($unit_id);
        $unit->delete();
        return response()->json(['success' => true]);
    }

    public function add_session(Request $req, $path, $subscription_id, $unit_id){
         try {
            $req->validate([
                'name' => 'required|max:190',
                'number'=>'required|max:30',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['success' => false, 'message' => $e->errors()]);
        }
        $session = new CurriculumSessions();
        $session->unit_id = $unit_id;
        $session->name = $req->input('name');
        $session->number = $req->input('number');
        $session->save();
        return response()->json(['success' => true]);
    }

    public function get_session($path, $subscription_id, $unit_id, $session_id){
        $session = CurriculumSessions::find($session_id);
        if(!$session)
            return response()->json(['success'=>false, 'message'=>[['not found']]]);
        return response()->json(['success' => true, 'data'=>$session]);
    }

    public function update_session(Request $req, $path, $subscription_id, $unit_id, $session_id){
        try {
            $req->validate([
                'id'=>'required',
                'name' => 'required|max:190',
                'number'=>'required|max:30',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['success' => false, 'message' => $e->errors()]);
        }
        $session = CurriculumSessions::find($req->input('id'));
        if(!$session){
            return response()->json(['success'=>false, 'message'=>[['not found']]]);
        }
        $session->unit_id = $unit_id;
        $session->name = $req->input('name');
        $session->number = $req->input('number');
        $session->save();
        return response()->json(['success' => true]);
    }

    public function delete_session($path, $subscription_id, $unit_id, $session_id){
        $session = CurriculumSessions::find($session_id);
        if(!$session){
            return response()->json(['success'=>false, 'message'=>[['not found']]]);
        }
        $session->delete();
        return response()->json(['success' => true]);
    }

    public function get_document($path, $package_id, $subscription_id, $unit_id, $id){
        $document = CurriculumDocument::find($id);
        if(!$document)
            return response()->json(['success'=>false, 'message'=>[['not found']]]);
        return response()->json(['success' => true, 'data'=>$document]);
    }

    public function add_document(Request $req, $path, $subscription_id, $unit_id, $session_id){
        try {
            $req->validate([
                'name' => 'required|max:190',
                'number' => 'required|max:30',
                'time' => 'required',
                'keywords' => 'required',
            ]);
            $type = $req->input('type');
            if ($type === 'file') {
                if (!$req->hasFile('file') || !$req->file('file')) {
                    throw ValidationException::withMessages([
                        'file' => ['A file must be uploaded when your Document Type is File.']
                    ]);
                }
            } elseif ($type === 'url') {
                if (!filter_var($req->input('url'), FILTER_VALIDATE_URL)) {
                    throw ValidationException::withMessages([
                        'url' => ['A valid URL must be entered when your Document Type is URL.']
                    ]);
                }
            }
        } catch (ValidationException $e) {
            return response()->json(['success' => false, 'message' => $e->errors()]);
        }

        $filenfo = '';
        $url = '';

        $fileDir = config('constant.file');
        if (!is_dir($fileDir)) {
            mkdir($fileDir);
        }

        if ($req->input('type') === 'file') {
            $file = $req->file('file');
            if (is_array($file)) {
                $file = $file[0];
            }
            if ($file && filesize($file)) {
                $filenfo = $file->getClientOriginalName();
                $file->move($fileDir, $filenfo);
            }
        } else {
            $url = $req->input('url', '');
        }


            $document = new CurriculumDocument([
                'unit_id'    => $unit_id,
                'session_id' => $session_id,
                'document'   => $filenfo,
                'number'     => $req->input('number'),
                'keywords'   => $req->input('keywords'),
                'name'       => $req->input('name'),
                'filenfo'    => $filenfo,
                'type'       => $req->input('type'),
                'url'        => $url,
                'time'       => $req->input('time'),
            ]);
            $document->save();
            return response()->json(['success' => true]);

    }
    public function update_document(Request $req, $path, $package_id, $unit_id, $session_id, $id){
        try {
            $req->validate([
                'id'=>'required',
                'name' => 'required|max:190',
                'number'=>'required|max:30',
            ]);
        } catch(ValidationException $e) {
            return response()->json(['success' => false, 'message' => $e->errors()]);
        }
        $document = CurriculumDocument::find($req->input('id'));
        if(!$document){
            return response()->json(['success'=>false, 'message'=>[['not found']]]);
        }
        /* Getting file name */
        $file = $req->file('file');
        if(is_array($req->file('file')))
            $file = $req->file('file')[0];
        if(filesize($file)) {
            $filenfo = $file->getClientOriginalName();
            $file_name = $filenfo;
            $extension = pathinfo($filenfo, PATHINFO_EXTENSION);
            unlink(config('constant.file').'/'.$document->document);
            $file->move(config('constant.file'), $file_name);//save new file
            $document->document = $filenfo;
            $document->filenfo = $filenfo;
        }
        $date = $req->input('timestamp');
        if($date == 2){
            $document->timestamps = false;
        }elseif($date == 1){
            $document->updated_at = now();
        }
        $document->unit_id    = $unit_id;
        $document->session_id = $session_id;
        $document->number     = $req->input('number');
        $document->keywords   = $req->input('keywords');
        $document->name       = $req->input('name');
        $document->time       = $req->input('time');
        $document->type       = $req->input('type');
        $document->url        = $req->input('url') ?? '';
        $document->save();
        return response()->json(['success' => true]);
    }

    public function delete_document($path, $subscription_id, $unit_id, $session_id, $document_id){
        $document = CurriculumDocument::find($document_id);
        if(!$document){
            return response()->json(['success'=>false, 'messaage'=>'Invalid document ID']);
        }
        @unlink(config('constant.file').'/'.$document->document);//supressing error if file not found
        $document->delete();
        return response()->json(['success' => true]);
    }

}
