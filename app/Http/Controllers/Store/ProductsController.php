<?php
namespace App\Http\Controllers\Store;
use App\Http\Controllers\Controller;
use App\Models\ActiveSubscriptions;
use App\Models\BulkPricing;
use App\Models\CurriculumDocument;
use App\Models\CurriculumPrice;
use App\Models\CurriculumSessions;
use App\Models\CurriculumUnits;
use App\Models\ProductAssignments;
use App\Models\ProductAvailability;
use App\Models\ProductFiles;
use App\Models\ProductVar;
use App\Models\Products;
use App\Models\ProductDescription;
use App\Models\ProductDetails;
use App\Models\ProductImages;
use App\Models\ProductStoreTags;
use App\Models\ProductTags;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
class ProductsController extends Controller
{
    private function invert_keys(){
        $new_arr = [];
        foreach(config('constant.types') as $key=>$val){
            $new_arr[$val] = $key;
        }
        return $new_arr;
    }

    /**
     * Validate digital slug requirements
     */
    private function validateDigitalSlug(?string $slug): array
    {
        if (!$slug || !strlen($slug)) {
            return ['error' => true, 'message' => 'Please type the digital slug.'];
        }

        preg_match('/[^a-z0-9\-]/i', $slug, $out);
        if (count($out)) {
            return ['error' => true, 'message' => 'The digital slug can only contain letters, numbers, and hyphens.'];
        }

        return ['error' => false, 'slug' => strtolower($slug)];
    }

    /**
     * Handle product images (upload and association)
     */
    private function handleProductImages(int $productId, array $images): string
    {
        // Start sort indexing at 1 (legacy rows may still be 0; accessor now handles fallback)
        $sort = 1;
        $firstImage = '';

        foreach ($images as $img) {
            if (is_file(config('constant.hold').'/'.$img)) {
                copy(
                    config('constant.hold').'/'.$img,
                    config('constant.product.upload').'/'.$img
                );

                ProductImages::create([
                    'image' => $img,
                    'sort' => $sort,
                    'product_id' => $productId,
                ]);

                if ($firstImage === '') {
                    $firstImage = $img;
                }
                $sort++;
            }
        }

        $this->cleanupTempFiles();
        return $firstImage;
    }

    /**
     * Clean up temporary uploaded files
     */
    private function cleanupTempFiles(): void
    {
        $tempFolder = config('constant.hold');
        $files = glob($tempFolder.'/*');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
    }

    /**
     * Set product properties based on type and request data
     */
    private function setProductProperties(Products $product, Request $req, bool $isDigital): void
    {
        $product->ref_id = 0;
        $product->name = $req->input('name');
        $product->slug = $req->input('slug');
        $product->description = $req->input('description');
        $product->tag = $req->input('ac_tags');
        $product->catergory = config('constant.types')[$req->input('type')];
        $product->taxable = $req->input('taxable');
        // Set sort order
        $maxSort = Products::max('sort') ?? 0;
        $product->sort = $maxSort + 1;

        if ($isDigital) {
            $product->ship_type = 0;
            $product->qty = 1000000;
        } else {
            $product->ship_type = $req->input('ship_type');
            $product->qty = $req->input('qty');
        }

        $product->avail = 1;
        $product->image = '';
        // Note: paypal_id is in Products table for legacy PayPal subscriptions, not ProductVar
        
        // Deliver slug for digital products, digital products is now defined in product_options as type
        if (in_array($req->input('type'), config('constant.digital'))) {
            $product->deliver_slug = strtolower($req->input('slug2'));
        }
    }

    /**
     * Attach tags to product
     */
    private function attachProductTags(int $productId, ?array $tags): void
    {
        if (!$tags) {
            return;
        }

        foreach ($tags as $tag) {
            ProductTags::create([
                'product_id' => $productId,
                'tag_id' => $tag,
            ]);
        }
    }

    /**
     * Create product details (for non-swag items)
     */
    private function createProductDetails(int $productId, Request $req): void
    {
        ProductDetails::create([
            'product_id' => $productId,
            'author' => $req->input('author', ''),
            'facilitator' => $req->input('facilitator', ''),
            'population' => $req->input('population', ''),
            'age' => $req->input('age', ''),
            'duration' => $req->input('duration', ''),
        ]);
    }

    //Master CRUD
    public function products($path = 'educators'){
        // New category mapping (normalized categories replacing legacy type codes)
        $categories = [
            1 => 'curriculum',
            2 => 'activity',
            3 => 'book',
            4 => 'game',
            5 => 'swag',
            6 => 'toolkit',
            7 => 'training',
        ];
        // Derive high-level digital categories (collapse digital/physical variants)
        $digitalCategories = ['curriculum','training','activity','book'];
        return view('backend.products.products')
            ->with('products', Products::with(['vars','images'])->orderBy('sort', 'ASC')->get())
            ->with('categories', $categories)
            ->with('section', 'backend')
            ->with('digital', $digitalCategories)
            ->with('path', get_path($path));
    }

    public function create($path = 'educators'){
        return view('backend.products.create')
        ->with('section', 'backend')
        ->with('path', get_path($path));
    }

    public function create_product(Request $req, $path = 'educators'){
        // Validation
        try {
            $req->validate([
                'name' => 'required|max:190',
                'slug' => ['required', 'alpha_dash', Rule::unique('products')],
                'description' => 'required',
                'ac_tags' => 'required|max:190',
                'category' => 'required|in:' . implode(',', config('constant.product_types')),
                'image' => 'required|array|min:1',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['error' => true, 'errors' => $e->errors()]);
        }

        $category = $req->input('category');
        $isDigital = in_array($category, config('constant.digital'));

        // Digital product specific validation
        if ($isDigital) {
            if (!$req->input('slug2') || !strlen($req->input('slug2'))) {
                return response()->json(['error' => true, 'message' => 'Please type the digital slug.']);
            }
            if (preg_match('/[^a-z0-9\-]/i', $req->input('slug2'))) {
                return response()->json(['error' => true, 'message' => 'The digital slug can only contain letters, numbers, and hyphens.']);
            }
        }

        // Physical product specific validation
        if (!$isDigital) {
            if (!filter_var($req->input('qty'), FILTER_VALIDATE_INT)) {
                return response()->json(['error' => true, 'message' => 'The quantity needs to be a number.']);
            }
            if ($category !== 'swag' && !filter_var($req->input('weight'), FILTER_VALIDATE_FLOAT)) {
                return response()->json(['error' => true, 'message' => 'The weight needs to be a number.']);
            }
            if (!in_array($req->input('ship_type'), [0, 1, 2], true)) {
                return response()->json(['error' => true, 'message' => 'Please select a shipping type.']);
            }
        }

        DB::beginTransaction();
        try {
            // Create product
            $product = new Products();
            $this->setProductProperties($product, $req, $isDigital);
            $product->save();

            // Handle product tags
            $this->attachProductTags($product->id, $req->input('tags'));

            // Handle product images
            $firstImage = '';
            // Start at 1 for new product for consistency with primary image logic (sort=1)
            $sort = 1;
            foreach ($req->input('image') as $img) {
                if (is_file(config('constant.hold') . '/' . $img)) {
                    copy(
                        config('constant.hold') . '/' . $img,
                        config('constant.product.upload') . '/' . $img
                    );
                    
                    ProductImages::create([
                        'image' => $img,
                        'sort' => $sort++, // first image gets sort=1
                        'product_id' => $product->id,
                    ]);
                    
                    if ($firstImage === '') {
                        $firstImage = $img;
                    }
                }
            }

            // Update product with first image
            $product->image = $firstImage;
            $product->save();

            // Add product details for non-swag items
            if ($category !== 'swag') {
                $this->createProductDetails($product->id, $req);
            }

            // Clean up temp files
            $this->cleanupTempFiles();

            DB::commit();
            return response()->json(['error' => false, 'product' => $product]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Product creation failed', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['error' => true, 'message' => 'Product creation failed. Please try again.']);
        }
    }
    
    public function get_product($path = 'educators', $id){
        $product = Products::with(['vars', 'images'])->find($id);
        if(!$product)
            return response()->json(['error' => true, 'message'=>"Product not found!"]);
        
        $images = $product->images()->orderBy('sort', 'ASC')->get();
        $details = ProductDetails::where('product_id', $product->id)->first();
        
        // Get first variant (or default values if no variant exists)
        $variant = $product->vars->first();
        
        return response()->json([
            'error' => false, 
            'product' => $product,
            'variant' => $variant,
            'images' => $images,
            'details' => $details,
            'tags' => $product->get_tags()
        ]);
    }

    public function get_variants($path = 'educators', $id){
        $product = Products::find($id);
        if(!$product)
            return response()->json(['error' => true, 'message'=>"Product not found!"]);
        
        $variants = ProductVar::where('product_id', $id)
            ->orderBy('sort', 'ASC')
            ->orderBy('var_id', 'ASC')
            ->get();
        
        // Get all option values grouped with option names and categories
        // Order by value_id to preserve insertion order (matches edit-option-values modal)
        // Use innerJoin to exclude orphaned values (values without a valid parent option)
        $values = DB::table('product_options_values as v')
            ->join('product_options as o','o.option_id','=','v.options_id')
            ->select('v.value_id','v.options_id','v.name','o.name as option_name','o.categories as option_categories')
            ->orderBy('v.value_id', 'asc')
            ->get()
            ->map(function($val){
                // Decode categories JSON to array for frontend
                if($val->option_categories){
                    $val->option_categories = json_decode($val->option_categories, true);
                }
                return $val;
            });
        
        // Get existing assignments for these variants
        $variantIds = $variants->pluck('var_id');
        $assignments_raw = DB::table('product_options_vars_junction as j')
            ->join('product_options_values as v', 'j.value_id', '=', 'v.value_id')
            ->join('product_options as o', 'v.options_id', '=', 'o.option_id')
            ->whereIn('j.var_id', $variantIds)
            ->select('j.var_id', 'j.value_id', 'v.name as value_name', 'o.name as option_name')
            ->get();
        
        $assignments = [];
        $variantOptions = [];
        foreach($assignments_raw as $r){
            $assignments[$r->var_id] = $assignments[$r->var_id] ?? [];
            $assignments[$r->var_id][] = $r->value_id;
            $variantOptions[$r->var_id] = $variantOptions[$r->var_id] ?? [];
            $variantOptions[$r->var_id][] = ['option' => strtolower($r->option_name), 'value' => strtolower($r->value_name)];
        }
        
        return response()->json([
            'error' => false, 
            'product' => $product,
            'variants' => $variants,
            'values' => $values,
            'assignments' => $assignments
        ]);
    }

    public function save_variants(Request $req, $path = 'educators'){
        $productId = $req->input('product_id');
        if(!$productId){
            return response()->json(['error' => true, 'message' => 'Product ID is required']);
        }

        $product = Products::find($productId);
        if(!$product){
            return response()->json(['error' => true, 'message' => 'Product not found']);
        }

        DB::beginTransaction();
        try {
            // Handle deletions first
            $deleteIds = $req->input('delete', []);
            if(!empty($deleteIds)){
                ProductVar::whereIn('var_id', $deleteIds)
                    ->where('product_id', $productId)
                    ->delete();
            }

            // Handle updates and new variants
            // Track mapping from temporary formId to real var_id for option assignments
            $formIdToVarId = [];
            $variants = $req->input('v', []);
            foreach($variants as $varId => $data){
                // Check if this is a new variant (string 'NEW', negative number, or non-numeric)
                // Cast to string for comparison to handle both string and numeric keys
                $varIdStr = (string)$varId;
                $isNew = ($varIdStr === 'NEW' || (is_numeric($varId) && intval($varId) < 0) || !is_numeric($varId));
                
                if($isNew){
                    // New variant - create
                    $variant = new ProductVar();
                    $variant->product_id = $productId;
                } else {
                    // Existing variant - update
                    $variant = ProductVar::where('var_id', $varId)
                        ->where('product_id', $productId)
                        ->first();
                    
                    if(!$variant){
                        continue; // Skip if not found
                    }
                }

                // Update all fields
                $variant->name = $data['name'] ?? '';
                $variant->sku = $data['sku'] ?? '';
                $shipType = intval($data['ship_type'] ?? 0);
                $deliverSlug = isset($data['deliver_slug']) ? strtolower($data['deliver_slug']) : '';
                
                // Validate: Digital variants (ship_type=0) require delivery slug
                if($shipType === 0 && empty($deliverSlug)){
                    DB::rollBack();
                    return response()->json([
                        'error' => true, 
                        'message' => 'Delivery slug is required for digital variants'
                    ]);
                }
                
                $variant->deliver_slug = $deliverSlug;
                $variant->price = floatval($data['price'] ?? 0);
                $variant->taxable = intval($data['taxable'] ?? 1);
                $variant->ship_type = $shipType;
                
                // Weight handling: Now using separate weight_lbs and weight_oz fields
                if ($shipType === 0) { 
                    // Digital - set weight to 0
                    $variant->weight_lbs = 0;
                    $variant->weight_oz = 0;
                } else {
                    // Physical - use submitted lbs and oz values
                    $lbs = intval($data['weight_lbs'] ?? 0);
                    $oz = intval($data['weight_oz'] ?? 0);
                    
                    // Validate oz is in range 0-15
                    if ($oz < 0 || $oz > 15) {
                        DB::rollBack();
                        return response()->json([
                            'error' => true,
                            'message' => "Ounces must be between 0 and 15. SKU: {$variant->sku}"
                        ]);
                    }
                    
                    $variant->weight_lbs = $lbs;
                    $variant->weight_oz = $oz;
                }
                $variant->qty = intval($data['qty'] ?? 0);
                $variant->avail = intval($data['avail'] ?? 1);
                $variant->sort = intval($data['sort'] ?? 0);
                $variant->save();
                
                // Map temporary formId to real var_id (for new variants and existing)
                // Store both string and numeric versions for PHP form data handling
                $formIdToVarId[$varId] = $variant->var_id;
                $formIdToVarId[(string)$varId] = $variant->var_id;
            }

            // Handle option assignments
            // New structure: assign[formId][optionId] = valueId (single value per option)
            $assign = $req->input('assign', []);
            if(!empty($assign)){
                // Get all variant IDs for this product
                $allVariantIds = ProductVar::where('product_id', $productId)->pluck('var_id')->toArray();
                
                // Clear existing assignments for these variants
                DB::table('product_options_vars_junction')
                    ->whereIn('var_id', $allVariantIds)
                    ->delete();
                
                // Insert new assignments
                $insertRows = [];
                foreach($assign as $formId => $optionValues){
                    // Map formId to real var_id (handles both new and existing variants)
                    // Lookup handles both string and numeric keys (PHP form data can vary)
                    $realVarId = $formIdToVarId[$formId] ?? null;
                    if(!$realVarId || !in_array($realVarId, $allVariantIds)) continue;
                    if(!is_array($optionValues)) continue;
                    
                    // Each option can only have one value selected
                    foreach($optionValues as $optionId => $valueId){
                        $valueId = (int)$valueId;
                        if($valueId <= 0) continue; // Skip empty selections
                        $insertRows[] = [
                            'var_id' => $realVarId,
                            'value_id' => $valueId,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }
                if(count($insertRows)){
                    DB::table('product_options_vars_junction')->insert($insertRows);
                }
            }

            DB::commit();
            
            // Return refreshed variants list with option assignments (ordered by sort column)
            $updatedVariants = ProductVar::where('product_id', $productId)
                ->orderBy('sort', 'ASC')
                ->orderBy('var_id', 'ASC')
                ->get();
            
            // Get option assignments
            $variantIds = $updatedVariants->pluck('var_id');
            $assignments = DB::table('product_options_vars_junction as j')
                ->join('product_options_values as v', 'j.value_id', '=', 'v.value_id')
                ->join('product_options as o', 'v.options_id', '=', 'o.option_id')
                ->whereIn('j.var_id', $variantIds)
                ->select('j.var_id', 'v.value_id', 'v.name as value_name', 'o.name as option_name', 'o.option_id')
                ->get()
                ->groupBy('var_id');
            
            // Attach assignments
            $updatedVariants = $updatedVariants->map(function($variant) use ($assignments) {
                $variant->option_assignments = $assignments->get($variant->var_id, collect())->toArray();
                return $variant;
            });

            return response()->json([
                'error' => false,
                'message' => 'Variants saved successfully',
                'variants' => $updatedVariants
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Variant save failed', [
                'product_id' => $productId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'error' => true,
                'message' => 'Failed to save variants: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Delete a single variant immediately
     */
    public function delete_variant(Request $req, $path = 'educators'){
        $productId = $req->input('product_id');
        $varId = $req->input('var_id');

        if(!$productId || !$varId){
            return response()->json(['error' => true, 'message' => 'Product ID and Variant ID are required']);
        }

        $product = Products::find($productId);
        if(!$product){
            return response()->json(['error' => true, 'message' => 'Product not found']);
        }

        $variant = ProductVar::where('var_id', $varId)
            ->where('product_id', $productId)
            ->first();

        if(!$variant){
            return response()->json(['error' => true, 'message' => 'Variant not found']);
        }

        DB::beginTransaction();
        try {
            // Delete option assignments for this variant
            DB::table('product_options_vars_junction')
                ->where('var_id', $varId)
                ->delete();

            // Delete the variant
            $variant->delete();

            DB::commit();
            return response()->json([
                'error' => false,
                'message' => 'Variant deleted successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Variant deletion failed', [
                'product_id' => $productId,
                'var_id' => $varId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'error' => true,
                'message' => 'Failed to delete variant: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Validate raw weight input before parsing to ensure ounces are in valid range (0-15).
     * Returns ['valid' => bool, 'message' => string]
     */
    private function validateWeightInput($raw): array
    {
        try {
            $str = is_string($raw) ? trim($raw) : strval($raw);
            if ($str === '' || $str === null || $str === '0' || $str === '0.00') {
                return ['valid' => true]; // Zero weight is valid
            }
            
            // Parse the decimal part to check ounces
            $num = floatval($str);
            $str = number_format($num, 2, '.', '');
            [$int, $frac] = array_pad(explode('.', $str, 2), 2, '0');
            $oz = (int)substr(str_pad($frac, 2, '0', STR_PAD_RIGHT), 0, 2);
            
            if ($oz > 15) {
                return [
                    'valid' => false,
                    'message' => "Invalid weight format: ounces must be 0-15 (got {$oz} oz). Enter weight as pounds.ounces (e.g., 2.05 for 2 lbs 5 oz)."
                ];
            }
            
            return ['valid' => true];
        } catch (\Throwable $e) {
            return [
                'valid' => false,
                'message' => 'Invalid weight format. Enter weight as pounds.ounces (e.g., 2.05 for 2 lbs 5 oz).'
            ];
        }
    }

    /**
     * Parse a weight field expressed as PP.OO where OO are ounces (00-15).
     * Returns [lbs(int), oz(int), ppoo(float formatted as pounds.ounces)]
     */
    private function parseWeightPPdotOO($raw): array
    {
        $lbs = 0; $oz = 0; $ppoo = 0.00;
        try {
            $str = is_string($raw) ? trim($raw) : strval($raw);
            if ($str === '' || $str === null) {
                return [0, 0, 0.00];
            }
            // Normalize to two decimals
            $num = floatval($str);
            $str = number_format($num, 2, '.', '');
            [$int, $frac] = array_pad(explode('.', $str, 2), 2, '0');
            $lbs = max(0, (int)$int);
            $oz = (int)substr(str_pad($frac, 2, '0', STR_PAD_RIGHT), 0, 2);
            if ($oz >= 16) {
                $add = intdiv($oz, 16);
                $lbs += $add;
                $oz = $oz % 16;
            }
            $ppoo = floatval($lbs . '.' . str_pad((string)$oz, 2, '0', STR_PAD_LEFT));
            return [$lbs, $oz, $ppoo];
        } catch (\Throwable $e) {
            return [0, 0, 0.00];
        }
    }

    public function update_product(Request $req){
        // Validation
        try {
            $req->validate([
                'id' => 'required|exists:products,id',
                'name' => 'required|max:190',
                'slug' => ['required', 'alpha_dash', Rule::unique('products')->ignore($req->input('id'))],
                'description' => 'required',
                'ac_tags' => 'nullable|max:190',
                'category' => 'required|integer|min:1|max:7',
                'sort' => 'required|numeric|min:1',
                'image' => 'nullable|array',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['error' => true, 'errors' => $e->errors()]);
        }

        $product = Products::find($req->input('id'));
        if (!$product) {
            return response()->json(['error' => true, 'message' => 'Product not found!']);
        }

        $categoryId = $req->input('category');

        DB::beginTransaction();
        try {
            // Update basic product properties
            $product->name = $req->input('name');
            $product->slug = $req->input('slug');
            $product->ac_tags = $req->input('ac_tags', '');
            $product->category = $categoryId;
            $product->sort = $req->input('sort');
            $product->save();

            // Get or create product variant
            $variant = ProductVar::firstOrNew(['product_id' => $product->id]);
            $variant->deliver_slug = $req->input('deliver_slug') ? strtolower($req->input('deliver_slug')) : '';
            $variant->taxable = $req->input('taxable', 1);
            $variant->ship_type = $req->input('ship_type', 0);
            // Normalize weight to PP.OO (OO=ounces) and store lbs/oz if available
            // Validate raw input before parsing (for physical products only)
            if ((int)$variant->ship_type !== 0) {
                $validation = $this->validateWeightInput($req->input('weight', 0));
                if (!$validation['valid']) {
                    DB::rollBack();
                    return response()->json([
                        'error' => true,
                        'message' => $validation['message']
                    ]);
                }
            }
            [$lbs, $oz, $ppoo] = $this->parseWeightPPdotOO($req->input('weight', 0));
            if ((int)$variant->ship_type === 0) { $lbs = 0; $oz = 0; }
            $variant->weight_lbs = $lbs;
            $variant->weight_oz = $oz;
            $variant->save();

            // Update tags - delete old ones and insert new
            ProductTags::where('product_id', $product->id)->delete();
            $this->attachProductTags($product->id, $req->input('tags'));

            // Update images efficiently
            $newImages = $req->input('image', []);
            
            if (!empty($newImages)) {
                $existingImages = ProductImages::where('product_id', $product->id)
                    ->pluck('image', 'image')
                    ->toArray();
                
                $firstImage = '';
                // Continue sort from current max so existing ordering is preserved
                $currentMaxSort = (int) ProductImages::where('product_id', $product->id)->max('sort');
                $sort = $currentMaxSort > 0 ? $currentMaxSort + 1 : 1;
                
                foreach ($newImages as $img) {
                    // Skip if image already exists
                    if (isset($existingImages[$img])) {
                        if ($firstImage === '') {
                            $firstImage = $img;
                        }
                        unset($existingImages[$img]);
                        continue;
                    }
                    
                    // Copy new image from temp
                    if (is_file(config('constant.hold') . '/' . $img)) {
                        copy(
                            config('constant.hold') . '/' . $img,
                            config('constant.product.upload') . '/' . $img
                        );
                        
                        ProductImages::create([
                            'image' => $img,
                            'sort' => $sort++, // assigns next sequential sort
                            'product_id' => $product->id,
                        ]);
                        
                        if ($firstImage === '') {
                            $firstImage = $img;
                        }
                    }
                }

                // Delete removed images
                if (!empty($existingImages)) {
                    ProductImages::where('product_id', $product->id)
                        ->whereIn('image', array_keys($existingImages))
                        ->delete();
                }
            }

            // Update product details for non-swag items (category 5 is swag)
            if ($categoryId != 5) {
                $details = ProductDetails::firstOrNew(['product_id' => $product->id]);
                $details->product_id = $product->id;
                $details->author = $req->input('eauthor', '');
                $details->facilitator = $req->input('efacilitator', '');
                $details->population = $req->input('epopulation', '');
                $details->age = $req->input('eage', '');
                $details->duration = $req->input('eduration', '');
                $details->save();
            }

            // Clean up temp files
            $this->cleanupTempFiles();

            DB::commit();
            return response()->json(['error' => false, 'product' => $product]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Product update failed', [
                'error' => $e->getMessage(), 
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => true, 'message' => 'Product update failed: ' . $e->getMessage()]);
        }
    }

    public function delete_product($path = 'educators', $id){
        $product = Products::find($id);
        if (!$product) {
            return response()->json(['error' => true, 'message' => 'Product not found!']);
        }

        DB::beginTransaction();
        try {
            // Delete product details (using product id, not reference_id which doesn't exist)
            ProductDetails::where('product_id', $product->id)->delete();

            // Category-specific cascading deletes
            // Note: The new schema uses 'category' field, not 'type'
            // Categories: 0=unknown, 1=curriculum, 2=activity, 3=book, 4=game, 5=swag, 6=toolkit, 7=training
            switch ($product->category) {
                case 1: // Curricula
                    $this->deleteCurriculaRelations($product->id);
                    break;

                case 5: // Swag
                    $this->deleteSwagRelations($product->id);
                    break;

                case 7: // Training
                    $this->deleteTrainingRelations($product->id);
                    break;

                // For standard products (activity, book, game, toolkit), just delete common relations
                case 2: case 3: case 4: case 6:
                default:
                    // Common relations will be deleted below
                    break;
            }

            // Get all variant IDs for this product before deleting variants
            $variantIds = ProductVar::where('product_id', $product->id)->pluck('var_id')->toArray();
            
            // Delete variant option assignments (junction table)
            if (!empty($variantIds)) {
                DB::table('product_options_vars_junction')
                    ->whereIn('var_id', $variantIds)
                    ->delete();
            }
            
            // Delete common relations (batch operations)
            ProductImages::where('product_id', $product->id)->delete();
            ProductTags::where('product_id', $product->id)->delete();
            // Delete product variants
            ProductVar::where('product_id', $product->id)->delete();
            // Delete curriculum pricing (if any)
            CurriculumPrice::where('product_id', $product->id)->delete();
            // Delete discount restrictions if any
            DB::table('discount_restrictions')->where('product_id', $product->id)->delete();
            // Delete the product itself
            $product->delete();

            DB::commit();
            return response()->json(['error' => false]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Product deletion failed', ['id' => $id, 'error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['error' => true, 'message' => 'Failed to delete product. Please try again.']);
        }
    }

    /**
     * Delete curricula-specific relations
     */
    private function deleteCurriculaRelations(int $productId): void
    {
        // Get org_ids for active subscriptions cleanup
        $orgIds = ProductAssignments::where('product_id', $productId)
            ->where('category', 1)
            ->join('users', 'product_assignments.user_id', '=', 'users.id')
            ->pluck('users.org_id')
            ->unique()
            ->toArray();

        // Delete active subscriptions
        if (!empty($orgIds)) {
            ActiveSubscriptions::where('product_id', $productId)
                ->where('category', 1)
                ->whereIn('org_id', $orgIds)
                ->delete();
        }

        // Delete package assignments
        ProductAssignments::where('product_id', $productId)->where('category', 1)->delete();

        // Delete curriculum structure (documents -> sessions -> units)
        $unitIds = CurriculumUnits::where('product_id', $productId)->pluck('id');
        if ($unitIds->isNotEmpty()) {
            $sessionIds = CurriculumSessions::whereIn('unit_id', $unitIds)->pluck('id');
            if ($sessionIds->isNotEmpty()) {
                CurriculumDocument::whereIn('session_id', $sessionIds)->delete();
            }
            CurriculumSessions::whereIn('unit_id', $unitIds)->delete();
        }
        CurriculumUnits::where('product_id', $productId)->delete();
    }

    /**
     * Delete swag-specific relations
     * Swag items now use ProductVar, so no special cleanup needed
     */
    private function deleteSwagRelations(int $productId): void
    {
        // ProductVar entries are handled by cascade delete or separate cleanup
        // Swag-specific data is now in product_options_vars_junction (size variants)
    }

    /**
     * Delete training-specific relations
     */
    private function deleteTrainingRelations(int $productId): void
    {
        // Get org_ids for active subscriptions cleanup
        $orgIds = ProductAssignments::where('product_id', $productId)
            ->where('category', 7)
            ->join('users', 'product_assignments.user_id', '=', 'users.id')
            ->pluck('users.org_id')
            ->unique()
            ->toArray();

        // Delete active subscriptions
        if (!empty($orgIds)) {
            ActiveSubscriptions::where('product_id', $productId)
                ->where('category', 7)
                ->whereIn('org_id', $orgIds)
                ->delete();
        }

        // Delete package assignments
        ProductAssignments::where('product_id', $productId)->where('category', 7)->delete();

        // Delete digital activities
        ProductFiles::where('product_id', $productId)->delete();
    }

    public function get_pricing($path, $id){
        $product = Products::find($id);
        $pricing = BulkPricing::where('product_id', $id)->orderBy('up_to', 'ASC')->get();
        return response()->json(['error' => false, 'pricings'=>$pricing, 'product'=>$product]);
    }

    public function save_pricing($path, Request $req){
        if(!$req->input('product_id'))
            return response()->json(['error' => true, 'message'=>'Invalid id']);
        $product = Products::find($req->input('product_id'));
        if(!$product)
            return response()->json(['error' => true, 'message'=>'Product not found']);

        if(!$req->input('pricing') || !$req->input('up_to')){
            return response()->json(['error' => true, 'message'=>'Pricing schema missing']);
        }
        $price = $product->price;
        $prices = $req->input('pricing');
        $pricing = BulkPricing::where('product_id', $req->input('product_id'))->orderBy('up_to', 'ASC')->get();
        $keyed_up = [];
        foreach($pricing as $p){
            $keyed_up[$p->up_to] = $p;
        }
        for($x = 0; $x < count($prices); $x++){
            $price = $prices[$x];
            $up = $req->input('up_to')[$x];
            if(isset($keyed_up[$up])){
                //update
                $p = $keyed_up[$up];
                unset($keyed_up[$up]);
            }else{
                $p = new BulkPricing();
                $p->product_id = $req->input('product_id');
                $p->up_to = $up;
            }
            $p->price = $price;
            $p->save();
        }
        if(count($keyed_up)){
            //something needs to be deleted
            foreach($keyed_up as $key=>$p){
                $p->delete();
            }
        }
        //fetch the min up_to and save it in product collection
        $price = BulkPricing::where('product_id', $req->input('product_id'))->orderBy('up_to', 'ASC')->first();
        if($price){
            $product->price = $price->price;
            $product->save();
        }
        return response()->json(['error' => false]);
    }

    public function update_availablity($path = 'educators', $id){
        // Toggle availability across all variants for this product
        $exists = Products::find($id);
        if(!$exists){
            return response()->json(['error' => true, 'message'=>'not found']);
        }
        $current = (int) DB::table('product_vars')->where('product_id', $id)->max('avail');
        $next = $current ? 0 : 1;
        DB::table('product_vars')->where('product_id', $id)->update(['avail'=>$next, 'updated_at'=>now()]);
        return response()->json(['error' => false, 'message'=>$next]);
    }

    public function save_available($path, Request $req){
        /**
         * @todo Implement fields validation
         */
        $product = Products::find($req->input('product_id'));
        if(!$product){
            return response()->json(['error' => true, 'message'=>'not found']);
        }
        // Update variant availability: 1 for available, 0 for unavailable
        $availabilityFlag = (int) ($req->input('availability') ? 1 : 0);
        DB::table('product_vars')->where('product_id', $product->id)->update(['avail'=>$availabilityFlag, 'updated_at'=>now()]);
        if($product->availability()){
            if($availabilityFlag == 0 || $req->input('available') == 'no'){
                //destroy the rules
                ProductAvailability::destroy($product->availability()->id);
            }else{
                //update
                $available = ProductAvailability::where('product_id', $product->id)->first();
            }
        }else{
            $available = new ProductAvailability();
            $available->product_id = $product->id;
        }
        if($availabilityFlag == 0 || $req->input('available') == 'no'){
            return response()->json(['error' => false, 'message'=>'done, no change']); //no need to update anything
        }
        $available->type = $req->input('available');
        $available->quantity = 0;
        if($available->type == 'quantity' || $available->type == 'both'){
            $available->quantity = $req->input('quantity');
        }
        if($available->type == 'time' || $available->type == 'both'){
            $start = DateTime::createFromFormat('m/d/Y', $req->input('start-date'));
            $start->setTime(0, 0);
            $stop = DateTime::createFromFormat('m/d/Y', $req->input('stop-date'));
            $stop->setTime(23, 59);
            $available->start = $start->format('Y-m-d H:i:s');
            $available->stop = $stop->format('Y-m-d H:i:s');
        }
        $available->save();
        return response()->json(['error' => false, 'message'=>'done']);
    }

    public function activity_digital($path, $id){
        $activities = ProductFiles::where('product_id', $id)->orderBy('sort', 'ASC')->get();
        $variants = ProductVar::where('product_id', $id)->get(['var_id', 'name', 'sku']);
        return response()->json([
            'error' => false, 
            'activities' => $activities, 
            'variants' => $variants,
            'id' => $id, 
            "file_path" => url(config('constant.product.path'))."/".$id."/"
        ]);
    }

    public function save_activities($path, Request $req){
        $rem = $req->input('rem');
        $id = $req->input('id');
        //REMOVE ACTIVITIES
        if($rem && strlen($rem)){
            $ids = explode("|", $rem);
            foreach($ids as $rid){
                ProductFiles::destroy($rid);
            }
        }
        //EDIT ACTIVITIES
        $x = 0;
        if($req->input('activity')){
            foreach($req->input('activity') as $aid=>$activity){
                $product_file = ProductFiles::find($aid);
                if(!$product_file)
                    continue;
                $source     = $req->input('source')[$aid];
                if($source == 1){
                    //keep current uploaded file unless new one has been uploaded
                    $resource = $product_file->resource;
                    if($req->file('file_upload') && isset($req->file('file_upload')[$aid])){
                        $file = $req->file('file_upload')[$aid];
                        if (filesize($file)) {
                            $filenfo = $file->getClientOriginalName();
                            $extension = pathinfo($filenfo, PATHINFO_EXTENSION);
                            if(!is_dir(config('constant.product.upload')."/".$req->input('id'))){
                                mkdir(config('constant.product.upload')."/".$req->input('id'));
                            }
                            $file_name = $filenfo;
                            $file->move(config('constant.product.upload')."/".$req->input('id'), $file_name);
                            $resource = $filenfo;
                        }
                    }
                }else{
                    $resource = $req->input('resource')[$aid];
                }
                
                // Handle variant assignments
                $variantIds = $req->input('variants');
                if(isset($variantIds[$aid]) && is_array($variantIds[$aid]) && count($variantIds[$aid]) > 0){
                    // Specific variants selected - store array of variant IDs
                    $product_file->variant_ids = array_map('intval', $variantIds[$aid]);
                } else {
                    // No specific variants selected = assigned to all variants (null)
                    $product_file->variant_ids = null;
                }
                $product_file->activity = $activity;
                $product_file->source   = $source;
                $product_file->resource = $resource;
                $product_file->sort     = $x;
                $product_file->save();
                $x++;
            }
        }
        //ADD NEW
        if($req->input('activity_new')){
            //new activities
            $x = 0;
            foreach($req->input('activity_new') as $activity){
                $tmp = [];
                // Validate activity field is not empty
                if(empty(trim($activity))){
                    return response()->json(['error' => true, 'message' => 'Please fill in the Activity field for each new activity!']);
                }
                $source = $req->input('source_new')[$x];
                $resource = ''; // Initialize resource variable
                if($source == 1){
                    if(!$req->file('file_upload_new') || !$req->file('file_upload_new')[$x]){
                        return response()->json(['error' => true, 'message' => 'Please upload all files!']);
                    }
                    $file = $req->file('file_upload_new')[$x];
                    if (filesize($file)) {
                        $filenfo = $file->getClientOriginalName();
                        $extension = pathinfo($filenfo, PATHINFO_EXTENSION);
                        if(!is_dir(config('constant.product.upload')."/".$req->input('id'))){
                            mkdir(config('constant.product.upload')."/".$req->input('id'));
                        }
                        $file_name = $filenfo;
                        $file->move(config('constant.product.upload')."/".$req->input('id'), $file_name);
                        $resource = $filenfo;
                    }
                }else{
                    if(!$req->input('resource_new')[$x]){
                        return response()->json(['error' => true, 'message' => 'Please fill the url resource!']);
                    }
                    $resource = $req->input('resource_new')[$x];
                }
                
                $product_file = new ProductFiles();
                $product_file->activity = $activity;
                $product_file->source = $source;
                $product_file->resource = $resource;
                $product_file->product_id = $req->input('id');
                $sort = ProductFiles::where('product_id', $id)->count();
                $product_file->sort = $sort;
                
                // Handle variant assignments for new activities
                $variantIdsNew = $req->input('variants_new');
                if($variantIdsNew && is_array($variantIdsNew) && count($variantIdsNew) > 0){
                    // Specific variants selected - store array of variant IDs
                    $product_file->variant_ids = array_map('intval', $variantIdsNew);
                } else {
                    // No specific variants selected = assigned to all variants (null)
                    $product_file->variant_ids = null;
                }
                
                $product_file->save();
                $x++;
            }
        }
        $activities = ProductFiles::where('product_id', $id)->orderBy('sort', 'ASC')->get();
        return response()->json(['error' => false, 'activities'=>$activities, 'id'=>$id]);
    }

    //STORE TAGS
    public function save_tags($path, Request $req){
        if($req->input('r')){
            foreach($req->input('r') as $id=>$root_id){
                $tag = ProductStoreTags::find($id);
                if(!$tag){
                    continue;
                }
                $tag->root_id = $root_id;
                if($tag->id != $req->input('c')[$id]){
                    $tag->parent_id = $req->input('c')[$id];
                }
                $tag->name = $req->input('n')[$id];
                $tag->save();
            }
        }
        $i = 0;
        $errors = [];
        if($req->input('root')){
            foreach($req->input('root') as $root_id){
                $parent_id = $req->input('category')[$i];
                $name = $req->input('name')[$i];
                if(!$name || !strlen($name)){
                    $errors[] = 'Row: '.$i.' needs a tag fill!';
                }else{
                    $tag = new ProductStoreTags();
                    $tag->root_id = $root_id;
                    $tag->parent_id = $parent_id;
                    $tag->name = $name;
                    $tag->save();
                }
                $i++;
            }
        }
        return response()->json(['error' => false]);
    }

    // Get product images for editing
    public function get_images($path = 'educators', $id){
        $product = Products::find($id);
        if(!$product)
            return response()->json(['error' => true, 'message'=>"Product not found!"]);
        
        $images = ProductImages::where('product_id', $id)->orderBy('sort', 'ASC')->get()->map(function($img){
            // Decode variant_ids JSON column
            if($img->variant_ids){
                $img->variant_ids = json_decode($img->variant_ids, true);
            }
            return $img;
        });
        
        $variants = ProductVar::where('product_id', $id)->get();
        
        return response()->json([
            'error' => false,
            'id' => $id,
            'images' => $images,
            'variants' => $variants,
            'image_path' => config('constant.product.path')
        ]);
    }

    // Save product images (create, update, delete with variant assignments)
    public function save_images($path, Request $req){
        $rem = $req->input('rem');
        $id = $req->input('id');
        
        // REMOVE IMAGES
        if($rem && strlen($rem)){
            $ids = explode("|", $rem);
            foreach($ids as $rid){
                $image = ProductImages::find($rid);
                if($image){
                    // Delete physical file if it exists
                    $filePath = config('constant.product.upload').'/'.$image->image;
                    if(file_exists($filePath)){
                        unlink($filePath);
                    }
                    $image->delete();
                }
            }
        }
        
        // EDIT EXISTING IMAGES
        if($req->input('image_id')){
            $sortOrder = 0;
            foreach($req->input('image_id') as $imageId => $value){
                $productImage = ProductImages::find($imageId);
                if(!$productImage)
                    continue;
                
                // Handle image replacement if new file uploaded
                if($req->hasFile("image_upload.$imageId")){
                    $file = $req->file("image_upload.$imageId");
                    if($file && $file->isValid()){
                        // Delete old image file
                        $oldPath = config('constant.product.upload').'/'.$productImage->image;
                        if(file_exists($oldPath)){
                            unlink($oldPath);
                        }
                        
                        // Upload new image
                        $filename = time().'_'.$file->getClientOriginalName();
                        $file->move(config('constant.product.upload'), $filename);
                        $productImage->image = $filename;
                    }
                }
                
                // Handle variant assignments
                $variantIds = $req->input('variants');
                if(isset($variantIds[$imageId]) && is_array($variantIds[$imageId]) && count($variantIds[$imageId]) > 0){
                    // Specific variants selected - store as JSON array
                    $productImage->variant_ids = json_encode(array_map('intval', $variantIds[$imageId]));
                } else {
                    // No specific variants = assigned to all variants (null)
                    $productImage->variant_ids = null;
                }
                
                $productImage->sort = $sortOrder;
                $productImage->save();
                $sortOrder++;
            }
        }
        
        // ADD NEW IMAGES
        if($req->hasFile('image_upload_new')){
            $sortCount = ProductImages::where('product_id', $id)->count();
            $newFiles = $req->file('image_upload_new');
            $variantsNew = $req->input('variants_new', []);
            
            foreach($newFiles as $index => $file){
                if($file && $file->isValid()){
                    // Upload image
                    $filename = time().'_'.$index.'_'.$file->getClientOriginalName();
                    $file->move(config('constant.product.upload'), $filename);
                    
                    $productImage = new ProductImages();
                    $productImage->product_id = $id;
                    $productImage->image = $filename;
                    $productImage->sort = $sortCount;
                    
                    // Handle variant assignments for new images
                    // The form sends variants_new as an associative array with timestamp keys
                    $variantIdsForThisImage = [];
                    foreach($variantsNew as $key => $variants){
                        if(is_array($variants)){
                            $variantIdsForThisImage = array_merge($variantIdsForThisImage, $variants);
                        }
                    }
                    
                    if(!empty($variantIdsForThisImage)){
                        $productImage->variant_ids = json_encode(array_map('intval', $variantIdsForThisImage));
                    } else {
                        // No specific variants = assigned to all (null)
                        $productImage->variant_ids = null;
                    }
                    
                    $productImage->save();
                    $sortCount++;
                }
            }
        }
        
        $images = ProductImages::where('product_id', $id)->orderBy('sort', 'ASC')->get();
        return response()->json(['error' => false, 'images'=>$images, 'id'=>$id]);
    }

    // Get product descriptions for editing modal
    public function get_descriptions($path = 'educators', $id){
        $product = Products::find($id);
        if(!$product)
            return response()->json(['error' => true, 'message'=>"Product not found!"]);
        
        $descriptions = ProductDescription::where('product_id', $id)
            ->orderBy('sort', 'ASC')
            ->get();
        // Note: variant_ids is already cast to array by the model, no need to decode
        
        $variants = ProductVar::where('product_id', $id)->get();
        
        return response()->json([
            'error' => false,
            'id' => $id,
            'descriptions' => $descriptions,
            'variants' => $variants
        ]);
    }

    // Save product descriptions (create, update, delete with variant assignments)
    public function save_descriptions($path, Request $req){
        $rem = $req->input('rem');
        $id = $req->input('id');
        
        // REMOVE DESCRIPTIONS
        if($rem && strlen($rem)){
            $ids = explode("|", $rem);
            foreach($ids as $rid){
                $description = ProductDescription::find($rid);
                if($description){
                    $description->delete();
                }
            }
        }
        
        // EDIT EXISTING DESCRIPTIONS
        if($req->input('description_id')){
            $sortOrder = 0;
            foreach($req->input('description_id') as $descId => $value){
                $productDescription = ProductDescription::find($descId);
                if(!$productDescription)
                    continue;
                
                // Update description content
                if($req->has("description_content.$descId")){
                    $productDescription->description = $req->input("description_content.$descId");
                }
                
                // Handle variant assignments
                $variantIds = $req->input('variants');
                if(isset($variantIds[$descId]) && is_array($variantIds[$descId]) && count($variantIds[$descId]) > 0){
                    // Specific variants selected - store as JSON array
                    $productDescription->variant_ids = json_encode(array_map('intval', $variantIds[$descId]));
                } else {
                    // No specific variants = assigned to all variants (null)
                    $productDescription->variant_ids = null;
                }
                
                $productDescription->sort = $sortOrder;
                $productDescription->save();
                $sortOrder++;
            }
        }
        
        // ADD NEW DESCRIPTIONS
        if($req->has('description_content_new')){
            $sortCount = ProductDescription::where('product_id', $id)->count();
            $newDescriptions = $req->input('description_content_new', []);
            $variantsNew = $req->input('variants_new', []);
            
            foreach($newDescriptions as $index => $content){
                if(!empty($content)){
                    $productDescription = new ProductDescription();
                    $productDescription->product_id = $id;
                    $productDescription->description = $content;
                    $productDescription->sort = $sortCount;
                    
                    // Handle variant assignments for new descriptions
                    $variantIdsForThis = [];
                    if(isset($variantsNew[$index]) && is_array($variantsNew[$index])){
                        $variantIdsForThis = $variantsNew[$index];
                    }
                    
                    if(!empty($variantIdsForThis)){
                        $productDescription->variant_ids = json_encode(array_map('intval', $variantIdsForThis));
                    } else {
                        // No specific variants = assigned to all (null)
                        $productDescription->variant_ids = null;
                    }
                    
                    $productDescription->save();
                    $sortCount++;
                }
            }
        }
        
        $descriptions = ProductDescription::where('product_id', $id)->orderBy('sort', 'ASC')->get();
        return response()->json(['error' => false, 'descriptions'=>$descriptions, 'id'=>$id]);
    }

    public function delete_tag($path, Request $req){
        $tagId = $req->input('id');
        
        if(!$tagId){
            return response()->json(['error' => true, 'message' => 'Tag ID is required']);
        }

        $tag = ProductStoreTags::find($tagId);
        
        if(!$tag){
            return response()->json(['error' => true, 'message' => 'Tag not found']);
        }

        // Check if this tag is used as a parent by other tags
        $childTags = ProductStoreTags::where('parent_id', $tagId)->count();
        
        if($childTags > 0){
            return response()->json([
                'error' => true, 
                'message' => 'Cannot delete this tag because it is used as a parent tag by ' . $childTags . ' other tag(s). Please reassign or delete the child tags first.'
            ]);
        }

        // Check if this tag is assigned to any products
        $productCount = ProductTags::where('tag_id', $tagId)->count();
        
        if($productCount > 0){
            return response()->json([
                'error' => true, 
                'message' => 'Cannot delete this tag because it is assigned to ' . $productCount . ' product(s). Please remove the tag from products first.'
            ]);
        }

        // Safe to delete
        $tag->delete();
        
        return response()->json(['error' => false, 'message' => 'Tag deleted successfully']);
    }

    // PRODUCT OPTIONS CRUD (basic)
    public function get_options($path = 'educators'){
        $options = \App\Models\ProductOptions::orderBy('option_id', 'ASC')->get();
        return response()->json(['error'=>false, 'options'=>$options]);
    }

    public function save_options($path, Request $req){
        $existing = $req->input('n'); // associative array option_id => name
        $existingCategories = $req->input('c'); // associative array option_id => categories JSON
        $new = $req->input('name');   // array of new names
        $newCategories = $req->input('categories'); // array of categories JSON for new options
        $errors = [];

        // Update existing
        if(is_array($existing)){
            foreach($existing as $id=>$name){
                $name = trim($name);
                if($name === ''){ $errors[] = "Option ID $id requires a name."; continue; }
                $opt = \App\Models\ProductOptions::find($id);
                if(!$opt){ continue; }
                $opt->name = $name;
                
                // Update categories if provided
                if(is_array($existingCategories) && isset($existingCategories[$id])){
                    $cats = json_decode($existingCategories[$id], true);
                    $opt->categories = is_array($cats) ? $cats : [];
                }
                
                $opt->save();
            }
        }

        // Create new
        if(is_array($new)){
            foreach($new as $idx=>$name){
                $name = trim($name);
                if($name === ''){ continue; }
                
                $cats = [];
                if(is_array($newCategories) && isset($newCategories[$idx])){
                    $decoded = json_decode($newCategories[$idx], true);
                    $cats = is_array($decoded) ? $decoded : [];
                }
                
                \App\Models\ProductOptions::create(['name'=>$name, 'categories'=>$cats]);
            }
        }

        if(count($errors)){
            return response()->json(['error'=>true, 'message'=>implode("\n", $errors)]);
        }
        return response()->json(['error'=>false]);
    }

    public function delete_option($path, Request $req){
        $id = $req->input('id');
        $cascade = $req->input('cascade'); // Allow explicit cascade parameter
        if(!$id){ return response()->json(['error'=>true, 'message'=>'Missing id']); }
        $opt = \App\Models\ProductOptions::find($id);
        if(!$opt){ return response()->json(['error'=>true, 'message'=>'Option not found']); }
        
        // Check for dependent records
        $values = DB::table('product_options_values')->where('options_id', $id)->get();
        $valueCount = $values->count();
        
        if($valueCount > 0){
            $valueIds = $values->pluck('value_id');
            $junctionCount = DB::table('product_options_vars_junction')->whereIn('value_id', $valueIds)->count();
            $valueNames = $values->pluck('name')->take(5)->toArray();
            
            // If cascade not explicitly confirmed, return detailed dependency info
            if(!$cascade){
                $valuesPreview = implode(', ', $valueNames);
                if($valueCount > 5) $valuesPreview .= '...';
                return response()->json([
                    'error'=>true,
                    'requiresCascade'=>true,
                    'valueCount'=>$valueCount,
                    'junctionCount'=>$junctionCount,
                    'valueNames'=>$valueNames,
                    'message'=>"Option '{$opt->name}' has $valueCount value(s) ($valuesPreview)" . 
                               ($junctionCount ? " and $junctionCount variant assignment(s)" : "") . 
                               ". Click 'Cascade Delete' to remove all dependencies."
                ]);
            }
            
            // Cascade delete: remove junction entries, then values, then option
            DB::transaction(function() use ($valueIds, $opt){
                DB::table('product_options_vars_junction')->whereIn('value_id', $valueIds)->delete();
                DB::table('product_options_values')->whereIn('value_id', $valueIds)->delete();
                $opt->delete();
            });
            
            return response()->json([
                'error'=>false, 
                'message'=>"Option '{$opt->name}' and $valueCount value(s) deleted (cascade)"
            ]);
        }
        
        // No dependencies, safe delete
        $opt->delete();
        return response()->json(['error'=>false, 'message'=>'Option deleted']);
    }

    public function delete_options($path, Request $req){
        $ids = $req->input('ids');
        if(!is_array($ids) || !count($ids)){
            return response()->json(['error'=>true, 'message'=>'No option ids provided']);
        }
        $deleted = 0; $missing = 0; $blocked = [];
        foreach($ids as $id){
            $opt = \App\Models\ProductOptions::find($id);
            if(!$opt){ $missing++; continue; }
            $valueIds = DB::table('product_options_values')->where('options_id', $id)->pluck('value_id');
            if($valueIds->count() > 0){
                $blocked[] = $id; // has children; skip
                continue;
            }
            $opt->delete();
            $deleted++;
        }
        $msg = "Deleted $deleted option(s).";
        if($missing){ $msg .= " $missing missing."; }
        if(count($blocked)){ $msg .= " Blocked: " . implode(',', $blocked) . " (has values)."; }
        return response()->json(['error'=>false, 'message'=>$msg]);
    }

    // OPTION VALUES CRUD
    public function get_option_values($path = 'educators'){
        $values = DB::table('product_options_values as v')
            ->leftJoin('product_options as o', 'o.option_id', '=', 'v.options_id')
            ->select('v.value_id','v.options_id','v.name', DB::raw('COALESCE(o.name, "") as option_name'))
            ->orderBy('v.value_id', 'asc')
            ->get();
        return response()->json(['error'=>false, 'values'=>$values]);
    }

    public function save_option_values($path, Request $req){
        $existing = $req->input('n'); // associative: value_id => name
        $newNames = $req->input('new_name'); // array of names
        $newOptions = $req->input('new_options_id'); // array of options_id aligning with new_name

        $errors = [];

        // Update existing values
        if(is_array($existing)){
            foreach($existing as $valueId=>$name){
                $name = trim($name ?? '');
                if($name === ''){ $errors[] = "Value ID $valueId requires a name."; continue; }
                $val = \App\Models\ProductOptionValues::find($valueId);
                if(!$val){ continue; }
                $val->name = $name;
                $val->save();
            }
        }

        // Create new values
        if(is_array($newNames) && is_array($newOptions)){
            $count = min(count($newNames), count($newOptions));
            for($i=0; $i<$count; $i++){
                $name = trim($newNames[$i] ?? '');
                $optId = $newOptions[$i] ?? null;
                if($name === '' || !$optId){ continue; }
                // Verify option exists
                $opt = \App\Models\ProductOptions::find($optId);
                if(!$opt){ $errors[] = "Invalid option selected for new value '$name'."; continue; }
                \App\Models\ProductOptionValues::create([
                    'options_id' => $optId,
                    'name' => $name,
                ]);
            }
        }

        if(count($errors)){
            return response()->json(['error'=>true, 'message'=>implode("\n", $errors)]);
        }
        return response()->json(['error'=>false, 'message'=>'Values saved']);
    }

    public function delete_option_value($path, Request $req){
        $id = $req->input('id');
        if(!$id){ return response()->json(['error'=>true, 'message'=>'Missing id']); }
        $value = \App\Models\ProductOptionValues::find($id);
        if(!$value){ return response()->json(['error'=>true, 'message'=>'Value not found']); }
        // Guard: prevent deletion if used in junction table
        $usage = DB::table('product_options_vars_junction')->where('value_id', $id)->count();
        if($usage > 0){
            return response()->json(['error'=>true, 'message'=>"Cannot delete value; it is assigned to $usage variant(s). Remove assignments first."]);
        }
        $value->delete();
        return response()->json(['error'=>false, 'message'=>'Value deleted']);
    }

    public function delete_option_values($path, Request $req){
        $ids = $req->input('ids');
        if(!is_array($ids) || !count($ids)){
            return response()->json(['error'=>true, 'message'=>'No value ids provided']);
        }
        $deleted = 0; $missing = 0; $blocked = [];
        foreach($ids as $id){
            $val = \App\Models\ProductOptionValues::find($id);
            if(!$val){ $missing++; continue; }
            $usage = DB::table('product_options_vars_junction')->where('value_id', $id)->count();
            if($usage > 0){ $blocked[] = $id; continue; }
            $val->delete();
            $deleted++;
        }
        $msg = "Deleted $deleted value(s).";
        if($missing){ $msg .= " $missing missing."; }
        if(count($blocked)){ $msg .= " Blocked: " . implode(',', $blocked) . " (assigned)."; }
        return response()->json(['error'=>false, 'message'=>$msg]);
    }

    /* ================= Variant Option Assignments ================= */
    /**
     * Return variants, option values, and existing assignments for a product.
     */
    public function get_variant_option_assignments($path, $id){
        $product = Products::find($id);
        if(!$product){
            return response()->json(['error'=>true, 'message'=>'Product not found']);
        }
        $variants = ProductVar::where('product_id', $id)->orderBy('var_id')->get(['var_id','sku','deliver_slug']);
        // All option values (joined with option names)
        $values = DB::table('product_options_values as v')
            ->leftJoin('product_options as o','o.option_id','=','v.options_id')
            ->select('v.value_id','v.options_id','v.name','o.name as option_name')
            ->orderBy('o.name')
            ->orderBy('v.name')
            ->get();
        // Existing assignments using junction table var_id column (legacy design)
        $variantIds = $variants->pluck('var_id');
        $rows = DB::table('product_options_vars_junction')
            ->whereIn('var_id', $variantIds)
            ->get(['var_id','value_id']);
        $assignments = [];
        foreach($rows as $r){
            $assignments[$r->var_id] = $assignments[$r->var_id] ?? [];
            $assignments[$r->var_id][] = $r->value_id;
        }
        return response()->json([
            'error'=>false,
            'product'=>['id'=>$product->id,'name'=>$product->name],
            'variants'=>$variants,
            'values'=>$values,
            'assignments'=>$assignments,
        ]);
    }

    /**
     * Save assignments of option value IDs to variants.
     * Expected payload: product_id, assign[var_id][] => value_id list
     */
    public function save_variant_option_assignments($path, Request $req){
        $productId = (int)$req->input('product_id');
        if(!$productId){
            return response()->json(['error'=>true,'message'=>'Missing product_id']);
        }
        $product = Products::find($productId);
        if(!$product){
            return response()->json(['error'=>true,'message'=>'Product not found']);
        }
        $assign = $req->input('assign'); // associative: var_id => [value_ids]
        if(!is_array($assign)){
            $assign = [];
        }
        $variantIds = ProductVar::where('product_id',$productId)->pluck('var_id')->toArray();
        DB::beginTransaction();
        try {
            // Remove existing rows for product variants (legacy var_id column)
            DB::table('product_options_vars_junction')->whereIn('var_id',$variantIds)->delete();
            // Insert new assignments
            $insertRows = [];
            foreach($assign as $varId=>$valueIds){
                $varId = (int)$varId;
                if(!in_array($varId, $variantIds)) continue; // skip invalid variant
                if(!is_array($valueIds)) continue;
                $seen = [];
                foreach($valueIds as $valId){
                    $valId = (int)$valId;
                    if($valId <= 0) continue;
                    if(isset($seen[$valId])) continue; // dedupe per variant
                    $seen[$valId] = true;
                    $insertRows[] = [
                        'var_id' => $varId,
                        'value_id'   => $valId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
            if(count($insertRows)){
                DB::table('product_options_vars_junction')->insert($insertRows);
            }
            DB::commit();
        } catch(\Throwable $e){
            DB::rollBack();
            return response()->json(['error'=>true,'message'=>'Save failed: '.$e->getMessage()]);
        }
        return response()->json(['error'=>false,'message'=>'Variant option assignments saved']);
    }
}
