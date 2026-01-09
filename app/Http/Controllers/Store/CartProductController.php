<?php

namespace App\Http\Controllers\Store;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Store\ShippingAPI;
use App\Models\Discounts;
use App\Models\Products;
use App\Models\ProductVar;
use App\Models\PurchaseCart;
use App\Models\PurchaseItem;
use App\Models\UserAddress;
use App\Services\USPSShippingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CartProductController extends Controller
{
    public function canceled($path = 'educators') {
        return view('store.cart.exit_canceled')
        ->with('path', get_path($path));
    }
    public function return(Request $request, $path = 'educators') {
        $request->session()->forget('cart_id');
        $request->session()->forget('shipping');
        return view('store.cart.exit_purchased')
        ->with('path', get_path($path));
    }
    
    public function cart_count(){
        $cart_id = (int) session('cart_id');
        $qty = 0;
        if($cart_id != 0 && $cart = PurchaseCart::find($cart_id)){
            if(count($cart->items)){
                foreach($cart->items as $item){
                    $qty += $item->quantity;
                }
            }
        }
        return $qty;
    }
    
    public function has_purchase_items(){
        return $this->cart_count() > 0 ? true : false;
    }

    //adds a Book or Manual to cart
    public function add_product_to_cart(Request $req, $path = 'educators'){
        $req->validate([
            'item_id' => 'bail|required|numeric',
            'var_id' => 'bail|required|numeric',
            'qty'=>'required|numeric'
        ]);
        $cart_id = (int) session('cart_id');
        if($cart_id == 0 || !$cart = PurchaseCart::find($cart_id)){
            //create a new cart
            $cart = new PurchaseCart();
            if(auth()->user()){
                $cart->user_id = auth()->user()->id;
            }
            $cart->save();
        }else if(auth()->user()){
            $cart = PurchaseCart::find($cart_id);
            $cart->user_id = auth()->user()->id;
            $cart->save();
        }
        session(['cart_id'=> $cart->id]);
        $product = Products::find($req->input('item_id'));
        if(!$product)
            return redirect()->back()->with('status', 'Product not found!');

        // Get the variant to determine price
        $variant = \App\Models\ProductVar::find($req->input('var_id'));
        if(!$variant)
            return redirect()->back()->with('status', 'Product variant not found!');

        //add item to cart (check by product+variant combination)
        if($cartItem = PurchaseItem::where('cart_id', $cart->id)
            ->where('product_id', $req->input('item_id'))
            ->where('var_id', $req->input('var_id'))
            ->first()){
            $qty = $cartItem->quantity + $req->input('qty');
            $cartItem->quantity = $qty;
            $cartItem->price = $variant->price * $qty;
            $cartItem->save();
        }else{
            $cartItem = new PurchaseItem();
            $cartItem->product_id = $req->input('item_id');
            $cartItem->var_id = $req->input('var_id');
            $cartItem->cart_id = $cart->id;
            $cartItem->quantity = $req->input('qty');
            $cartItem->price = $variant->price * $req->input('qty');
            $cartItem->save();
        }
        return redirect()->to($path.'/cart_products')
        ->with('path', get_path($path));
    }

    //shows the cart details and options to update the cart @return type
    public function view_cart($path = 'educators'){
        $rows = [];
        $total = 0;
        $cart_id = (int) session('cart_id');
        if($cart_id == 0 || !$cart = PurchaseCart::find($cart_id)){
            //create a new cart
            $cart = new PurchaseCart();
            if(auth()->user()){
                $cart->user_id = auth()->user()->id;
            }
            $cart->save();
            session(['cart_id'=> $cart->id]);
        }
        if($cart_id != 0 && $cart = PurchaseCart::find($cart_id)){
            if(count($cart->items)){
                foreach($cart->items as $item){
                    $row = [];
                    $product = Products::find($item->product_id);
                        
                        // Get variant information
                        $variantName = '';
                        $variantPrice = 0;
                        if ($item->var_id) {
                            $variant = \App\Models\ProductVar::find($item->var_id);
                            if ($variant) {
                                // First try to get option values from junction table
                                try {
                                    $optionValues = \DB::table('product_options_vars_junction')
                                        ->join('product_options_values', 'product_options_vars_junction.value_id', '=', 'product_options_values.value_id')
                                        ->where('product_options_vars_junction.var_id', $variant->var_id)
                                        ->orderBy('product_options_values.value_id')
                                        ->pluck('product_options_values.name')
                                        ->toArray();
                                    
                                    if (!empty($optionValues)) {
                                        // Use option values if they exist
                                        $variantName = "\n[" . implode(', ', $optionValues) . ']';
                                    } elseif ($variant->name) {
                                        // Fall back to variant name if no options
                                        $variantName = "\n[" . $variant->name . ']';
                                    }
                                } catch (\Exception $e) {
                                    // If query fails, fall back to variant name
                                    if ($variant->name) {
                                        $variantName = "\n[" . $variant->name . ']';
                                    }
                                }
                                
                                if ($variant->price) {
                                    $variantPrice = $variant->price;
                                }
                            }
                        }
                        
                    $row['product'] = $product->name . $variantName;
                    $row['slug']= $product->slug;
                    $row['category'] = $product->category;
                    $row['product_id'] = $item->product_id;
                    $row['qty'] = $item->quantity;
                    $row['cost'] = $item->price;
                    $row['id'] = $item->id;
                    $row['price'] = $variantPrice ?: $product->get_price_for_range($item->quantity);
                    $row['reference_id'] = $product->id;
                    $total += $row['cost'];
                    $rows[] = $row;
                }
            }
        }
        return view('store.cart.cart_products')
        ->with('rows', $rows)
        ->with('cart', $cart)
        ->with('discount', session('discount'))
        ->with('total', $total)
        ->with('section3', 'cart')
        ->with('path', get_path($path));
    }

    //update the cart and if checkout=1, forward to login, if auth=logged in, forward to address info, otherwise it returns to cart content (cart_checkout method)
    public function updateCart(Request $req, $path = 'educators'){
        $cart_id = (int) session('cart_id');
        if($cart_id != 0 && $cart = PurchaseCart::find($cart_id)){
            $price = 0;
            if($req->input('rems')){
                $rems = explode(",", $req->input('rems'));
                if(count($rems)){
                    foreach($rems as $rem){
                        if(strlen($rem)){
                            PurchaseItem::find($rem)->delete();
                        }
                    }
                }
            }
            if($req->input('qty')){
                foreach($req->input('qty') as $id=>$qty){
                    if($qty <=0){
                        PurchaseItem::find($id)->delete();
                    }else{
                        $cartItem = PurchaseItem::find($id);

                        if($cartItem && $product = Products::find($cartItem->product_id)){
                            // Get the variant price (not the product base price)
                            $variant = ProductVar::where('var_id', $cartItem->var_id)->first();
                            $unitPrice = $variant ? $variant->price : $product->get_price_for_range($qty);
                            
                            $price += $qty * $unitPrice;
                            $cartItem->quantity = $qty;
                            $cartItem->price = $unitPrice * $qty;
                            $cartItem->save();
                        }
                    }
                }
            }
            $cart->total = $price;
            $cart->save();
            if($req->input('discount-code') &&
                $discount = Discounts::where('code', 'LIKE', $req->input('discount-code'))->where('status', Discounts::DISCOUNT_AVAILABLE)->first()
            ){
                session(['discount'=> $req->input('discount-code')]);
            }else{
                session(['discount'=> null]);
            }
        }
        //forward to login or if auth=logged in, forward to address info
        if($req->input('checkout') && $req->input('checkout') == 1){
            if(auth()->user()){ 
                return redirect()->to($path.'/checkout_address');
            }else{
                return redirect()->to($path.'/cart_login');
            }
        }else{
            return response()->json(['success'=>true]);
        }
    }

    //login or forward to address
    public function cart_login($path = 'educators'){
        $cart_id = (int) session('cart_id');
        $user_id = auth()->user() ? auth()->user()->id : 0;
        session(['purchaser'=> true]);
        session(['path'=> get_path($path)]); // Store path for post-login redirect
        return view('store.cart.cart_login')
            ->with('path', get_path($path))
            ->with('section3', 'login');
    }


    //cart details (login, address)
    public function address($path = 'educators'){
        $user_id = auth()->user() ? auth()->user()->id : 0;
        $cart_id = (int) session('cart_id');
        $cart = PurchaseCart::find($cart_id);
        if($cart_id == 0 || !$cart || $user_id == 0)
            abort(404);
        $cart->user_id = $user_id;
        $cart->save();
        
        // Note: We no longer skip address page for digital-only orders
        // Tax calculation requires a billing address even for digital products
        
        $states = [
            'AL'=>'Alabama',
            'AK'=>'Alaska',
            'AZ'=>'Arizona',
            'AR'=>'Arkansas',
            'CA'=>'California',
            'CO'=>'Colorado',
            'CT'=>'Connecticut',
            'DE'=>'Delaware',
            'DC'=>'District Of Columbia',
            'FL'=>'Florida',
            'GA'=>'Georgia',
            'HI'=>'Hawaii',
            'ID'=>'Idaho',
            'IL'=>'Illinois',
            'IN'=>'Indiana',
            'IA'=>'Iowa',
            'KS'=>'Kansas',
            'KY'=>'Kentucky',
            'LA'=>'Louisiana',
            'ME'=>'Maine',
            'MD'=>'Maryland',
            'MA'=>'Massachusetts',
            'MI'=>'Michigan',
            'MN'=>'Minnesota',
            'MS'=>'Mississippi',
            'MO'=>'Missouri',
            'MT'=>'Montana',
            'NE'=>'Nebraska',
            'NV'=>'Nevada',
            'NH'=>'New Hampshire',
            'NJ'=>'New Jersey',
            'NM'=>'New Mexico',
            'NY'=>'New York',
            'NC'=>'North Carolina',
            'ND'=>'North Dakota',
            'OH'=>'Ohio',
            'OK'=>'Oklahoma',
            'OR'=>'Oregon',
            'PA'=>'Pennsylvania',
            'RI'=>'Rhode Island',
            'SC'=>'South Carolina',
            'SD'=>'South Dakota',
            'TN'=>'Tennessee',
            'TX'=>'Texas',
            'UT'=>'Utah',
            'VT'=>'Vermont',
            'VA'=>'Virginia',
            'WA'=>'Washington',
            'WV'=>'West Virginia',
            'WI'=>'Wisconsin',
            'WY'=>'Wyoming'
        ];
        $cart_id = (int) session('cart_id');
        $user_id = auth()->user() ? auth()->user()->id : 0;
        /**
         * check if we have an address stored for
         * 1. this cart id (already completed once) or
         * 2. previously filled address
         */
        $address = ShippingAddress::where('cart_id', $cart_id)->first();
        if(!$address && $user_id != 0){
            $address = ShippingAddress::where('cart_id', $user_id)->orderBy('cart_id', 'DESC')->first();
        }
        if(!$address){
            $address = new UserAddress();
            $address->name = auth()->user()? auth()->user()->name : "";
            $address->email= auth()->user()? auth()->user()->email : "";
        }
        return view('store.cart.checkout_address')
            ->with('path', get_path($path))
            ->with('section3', 'address')
            ->with('address', $address)
            ->with('states', $states);
    }

    public function test_api(){
        $address = '255-237 Clendenny Ave';
        $city = 'Jersey City';
        $state = 'NJ';
        $zip = '07304';
        $api = new ShippingAPI();
        $res = $api->check_address([
            'address'=>$address,
            'city'=>$city,
            'state'=>$state,
            'zip'=>$zip
        ]);
        if(!$res){
            dd($api->get_last_message());
        }
    }

    /**
     * Test USPS API connectivity - Debug endpoint
     */
    public function testUspsConnectivity()
    {
        $usps = new USPSShippingService();
        
        $tokenTest = $usps->testOAuthToken();
        $apiTest = $usps->testApiConnectivity();
        
        return response()->json([
            'oauth_token_test' => $tokenTest,
            'api_connectivity_test' => $apiTest,
            'mode' => config('usps.mode'),
            'base_url' => config('usps.endpoints.' . config('usps.mode') . '.base'),
        ]);
    }

    //checks validity of a discount code
    public function check_discount(Request $req, $path = 'educators'){
        if(!$req->input('code') || strlen($req->input('code')) == 0){
            $applied = [
                'code'=>'',
                'value'=>'',
                'new_total'=>$req->input('current')
            ];
            $req->session()->forget('discount');
            return response()->json(['error'=>false, 'discount'=>$applied]);
        }
        $discount = Discounts::where('code', 'LIKE', $req->input('code'))->where('status', Discounts::DISCOUNT_AVAILABLE)->first();
        if(!$discount){
            $req->session()->forget('discount');
            return response()->json(['error'=>true, 'message'=>'Invalid Discount Code']);
        }
        if($discount->expire_date != null && strtotime($discount->expire_date) < time()){
            $req->session()->forget('discount');
            return response()->json(['error'=>true, 'message'=>'Discount Code is Expired']);
        }
        if($discount->discount_type == Discounts::TYPE_FIXED){
            $value = "$".$discount->value;
            $new_total = $req->input('current') - $discount->value;
            if($new_total < 1){
                $req->session()->forget('discount');
                return response()->json(['error'=>true, 'message'=>'Discount Code Value is greater than your order!']);
            }
        }else{
            $value = $discount->value."%";
            $new_total = $req->input('current') - (($req->input('current') / 100) * $discount->value);
        }
        $applied = [
            'code'=>$discount->code,
            'value'=>$value,
            'new_total'=> number_format($new_total, 2)
        ];
        return response()->json(['error'=>false, 'discount'=>$applied]);
    }

    /**
     * Save a new or updated shipping address
     */
    public function saveAddress(Request $request, $path = 'educators')
    {
        try {
            $validated = $request->validate([
                'address_id' => 'nullable|integer|exists:user_addresses,id',
                'name' => 'required|string|max:255',
                'company' => 'nullable|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'required|string|max:20',
                'country' => 'required|string|size:2',
                'street' => 'required|string|max:255',
                'city' => 'required|string|max:255',
                'state' => 'nullable|required_if:country,US|string|max:2',
                'province' => 'nullable|required_unless:country,US|string|max:255',
                'zip' => 'required|string|max:20',
                'default' => 'nullable|boolean',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::warning('Address validation failed', [
                'input' => $request->except(['_token', 'password']),
                'errors' => $e->errors()
            ]);
            throw $e;
        }

        // Prepare address data
        $addressData = [
            'name' => $validated['name'],
            'company' => $validated['company'] ?? null,
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'country' => $validated['country'],
            'street' => $validated['street'],
            'city' => $validated['city'],
            'state_province' => $validated['country'] == 'US' ? $validated['state'] : ($validated['province'] ?? null),
            'zip' => $validated['zip'],
        ];

        // Validate/normalize with USPS before saving (but don't block save if validation fails)
        $validationResult = $this->performUspsValidation(
            new UserAddress(array_merge($addressData, ['user_id' => auth()->id()]))
        );

        if (empty($validationResult['normalized']) && $validationResult['success'] && isset($addressData['state_province'])) {
            // If USPS validation succeeded but didn't normalize, keep original data
            // This handles both validated and non-US addresses
        }

        if (!empty($validationResult['normalized'])) {
            // USPS provided normalized address data - use it
            $addressData = array_merge($addressData, $validationResult['normalized']);
        }

        // Note: We proceed with save even if USPS validation failed or is unavailable
        // Users should not be blocked from saving their addresses

        // Check if editing existing address
        if($request->input('address_id')){
            $address = UserAddress::forUser(auth()->id())
                ->findOrFail($request->input('address_id'));
            $message = 'Address updated successfully';
        } else {
            // Check for duplicate address (including soft-deleted ones)
            $duplicate = UserAddress::forUser(auth()->id())
                ->withTrashed()
                ->where('name', $addressData['name'])
                ->where('street', $addressData['street'])
                ->where('city', $addressData['city'])
                ->where('state_province', $addressData['state_province'])
                ->where('zip', $addressData['zip'])
                ->where('country', $addressData['country'])
                ->first();

            if($duplicate){
                if($duplicate->trashed()){
                    // Restore soft-deleted address instead of creating new one
                    $duplicate->restore();
                    $address = $duplicate;
                    $message = 'Address restored successfully';
                } else {
                    // Active duplicate exists
                    return response()->json([
                        'success' => false,
                        'message' => 'This address already exists in your address book.'
                    ], 422);
                }
            } else {
                // Create new address
                $address = new UserAddress();
                $address->user_id = auth()->id();
                $message = 'Address saved successfully';
            }
        }

        // Save address data
        $address->name = $addressData['name'];
        $address->company = $addressData['company'];
        $address->email = $addressData['email'];
        $address->phone = $addressData['phone'];
        $address->country = $addressData['country'];
        $address->street = $addressData['street'];
        $address->city = $addressData['city'];
        $address->state_province = $addressData['state_province'];
        $address->zip = $addressData['zip'];
        $address->save();

        // Set as default if requested
        if($request->input('default')){
            $address->setAsDefault();
        }

        return response()->json([
            'success' => true,
            'address_id' => $address->id,
            'message' => $message
        ]);
    }

    /**
     * Validate address with USPS API
     */
    public function validateAddress(Request $request, $path = 'educators')
    {
        $address = UserAddress::forUser(auth()->id())
            ->findOrFail($request->input('address_id'));

        $original = [
            'street' => $address->street,
            'city' => $address->city,
            'state_province' => $address->state_province,
            'zip' => $address->zip,
            'country' => $address->country,
        ];

        $result = $this->performUspsValidation($address);

        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'message' => $result['message'] ?? 'Address validation failed'
            ], 422);
        }

        $normalized = $result['normalized'] ?? [];

        if (!empty($normalized)) {
            $address->fill($normalized);
            $address->save();
        }

        $corrected = $this->addressWasCorrected($original, $address->only(['street', 'city', 'state_province', 'zip']));

        return response()->json([
            'success' => true,
            'validated' => $result['validated'] ?? false,
            'corrected' => $corrected,
            'original_address' => $original,
            'validated_address' => $address->only(['street', 'city', 'state_province', 'zip', 'country']),
            'message' => $result['message'] ?? null,
        ]);
    }

    /**
     * Get USPS shipping rates for cart
     */
    public function getShippingRates(Request $request, $path = 'educators')
    {
        $addressId = $request->input('address_id');
        $cartId = session('cart_id');

        if (!$addressId || !$cartId) {
            return response()->json([
                'success' => false,
                'message' => 'Address and cart are required'
            ], 400);
        }

        $address = ShippingAddress::findOrFail($addressId);
        $cart = PurchaseCart::with(['items.product', 'items.variant'])->findOrFail($cartId);

        $usps = new \App\Services\USPSShippingService();

        // Check if cart is digital only
        if ($usps->isDigitalOnly($cart)) {
            return response()->json([
                'success' => true,
                'digital_only' => true,
                'rates' => []
            ]);
        }

        // Get rates based on country
        try {
            if ($address->country === 'US') {
                $result = $usps->getDomesticRates($cart, $address);
                
                // TEMPORARY: If USPS API fails, provide fallback rates
                if (isset($result['error']) && $result['error']) {
                    \Log::warning('USPS API unavailable, using fallback rates', [
                        'message' => $result['message']
                    ]);
                    
                    $result = [
                        'rates' => [
                            [
                                'service' => 'GROUND_ADVANTAGE',
                                'display_name' => 'USPS Ground Advantage',
                                'rate' => 5.00,
                                'delivery_days' => '2-5 business days',
                                'zone' => null
                            ],
                            [
                                'service' => 'PRIORITY_MAIL',
                                'display_name' => 'USPS Priority Mail',
                                'rate' => 10.00,
                                'delivery_days' => '1-3 business days',
                                'zone' => null
                            ]
                        ]
                    ];
                }
            } else {
                $result = $usps->getInternationalRates($cart, $address);
                
                // TEMPORARY: International fallback
                if (isset($result['error']) && $result['error']) {
                    $result = [
                        'rates' => [
                            [
                                'service' => 'FIRST_CLASS_PACKAGE_INTERNATIONAL_SERVICE',
                                'display_name' => 'USPS First-Class International',
                                'rate' => 15.00,
                                'delivery_days' => '7-21 business days',
                                'zone' => null
                            ],
                            [
                                'service' => 'PRIORITY_MAIL_INTERNATIONAL',
                                'display_name' => 'USPS Priority Mail International',
                                'rate' => 40.00,
                                'delivery_days' => '6-10 business days',
                                'zone' => null
                            ]
                        ]
                    ];
                }
            }

            if (isset($result['error']) && $result['error']) {
                return response()->json([
                    'success' => false,
                    'message' => $result['message']
                ], 400);
            }

            return response()->json([
                'success' => true,
                'rates' => $result['rates'] ?? []
            ]);
        } catch (\Exception $e) {
            \Log::error('Shipping rate calculation failed', [
                'message' => $e->getMessage(),
                'address_id' => $addressId,
                'cart_id' => $cartId
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Unable to calculate shipping rates. Please try again or contact support.'
            ], 500);
        }
    }

    /**
     * Validate address with USPS (new implementation)
     */
    public function validateAddressUSPS(Request $request, $path = 'educators')
    {
        $address = ShippingAddress::forUser(auth()->id())
            ->findOrFail($request->input('address_id'));

        return $this->buildValidationResponse($address);
    }

    /**
     * Run USPS validation and format the address before persisting.
     */
    private function buildValidationResponse(ShippingAddress $address)
    {
        $original = $address->only(['street', 'city', 'state_province', 'zip', 'country']);

        try {
            $result = $this->performUspsValidation($address);

            if (!$result['success']) {
                return response()->json([
                    'success' => false,
                    'message' => $result['message'] ?? 'Address validation failed'
                ], 422);
            }

            $normalized = $result['normalized'] ?? [];

            if (!empty($normalized)) {
                $address->fill($normalized);
                $address->save();
            }

            $corrected = $this->addressWasCorrected($original, $address->only(['street', 'city', 'state_province', 'zip']));

            return response()->json([
                'success' => true,
                'validated' => $result['validated'] ?? false,
                'corrected' => $corrected,
                'original_address' => $original,
                'validated_address' => $address->only(['street', 'city', 'state_province', 'zip', 'country']),
                'message' => $result['message'] ?? null,
            ]);
        } catch (\Exception $e) {
            \Log::error('Address validation failed', [
                'message' => $e->getMessage(),
                'address_id' => $address->id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Unable to validate address. Please try again.'
            ], 500);
        }
    }

    private function performUspsValidation(ShippingAddress $address): array
    {
        if ($address->country !== 'US') {
            return [
                'success' => true,
                'validated' => false,
                'normalized' => null,
                'message' => 'International address validation not available'
            ];
        }

        $usps = new USPSShippingService();
        $result = $usps->validateAddress($address);

        if (!$result['success']) {
            $message = $result['message'] ?? 'Address validation failed';
            
            // Provide more specific guidance based on common USPS errors
            $fieldErrors = $result['field_errors'] ?? [];
            if (!empty($fieldErrors)) {
                $errorDetails = [];
                foreach ($fieldErrors as $field => $error) {
                    $errorDetails[] = ucfirst($field) . ": $error";
                }
                $message .= '. Issues: ' . implode('; ', $errorDetails);
            }
            
            // Always allow save even if validation fails
            Log::info('USPS validation failed but allowing save', [
                'address_id' => $address->id,
                'message' => $message,
            ]);
            
            return [
                'success' => true,
                'validated' => false,
                'normalized' => null,
                'message' => 'Address saved. ' . $message
            ];
        }

        return [
            'success' => true,
            'validated' => $result['validated'] ?? false,
            'normalized' => $this->normalizeUspsAddress($result['address'] ?? []),
            'message' => $result['message'] ?? null,
        ];
    }

    private function normalizeUspsAddress(array $address): array
    {
        return array_filter([
            'street' => $address['streetAddress'] ?? $address['address1'] ?? $address['deliveryLine1'] ?? $address['street'] ?? $address['address'] ?? null,
            'city' => $address['city'] ?? null,
            'state_province' => $address['state'] ?? ($address['state_province'] ?? null),
            'zip' => $address['ZIPCode'] ?? ($address['zip'] ?? $address['zip5'] ?? $address['postalCode'] ?? null),
        ], function ($value) {
            return !is_null($value) && $value !== '';
        });
    }

    private function addressWasCorrected(array $original, array $validated): bool
    {
        return (
            ($validated['street'] ?? $original['street']) !== $original['street'] ||
            ($validated['city'] ?? $original['city']) !== $original['city'] ||
            ($validated['state_province'] ?? $original['state_province']) !== $original['state_province'] ||
            ($validated['zip'] ?? $original['zip']) !== $original['zip']
        );
    }

    /**
     * Save selected shipping method to session
     */
    public function selectShipping(Request $request, $path = 'educators')
    {
        $validated = $request->validate([
            'address_id' => 'required|integer',
            'shipping_method' => 'nullable|string', // Allow null for digital-only orders
            'add_insurance' => 'nullable|boolean'
        ]);

        // Store in session for checkout
        session([
            'shipping_address_id' => $validated['address_id'],
            'shipping_method' => $validated['shipping_method'] ?? 'digital',
            'add_insurance' => $request->input('add_insurance', false)
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Selection saved successfully'
        ]);
    }
}
