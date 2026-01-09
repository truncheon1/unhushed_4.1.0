<!-- PRODUCT PRICE BOX -->
<div class="product-cart-form">
    <!-- Purchase Curriculum -->
    @if($product->category == 1)
        @php
            // Curriculum pricing should be eager loaded in controller
            $curriculumPricing = $product->curriculumPrices;
            $defaultPricing = $curriculumPricing->first();
        @endphp
        @if($curriculumPricing->isNotEmpty())
        <!-- start the form -->
        <form class="cart-form" action="{{ url($path.'/cart_subscriptions') }}" method="get">
            <input type="hidden" name="type[{{ $product->id }}]" value="{{ \App\Models\OrderItems::ITEM_TYPE_SUBSCRIPTION }}"/>
            <input type="hidden" name="id[]" value="{{ $product->id }}" />
            {{-- Free Curriculum Resources --}}
            @if($defaultPricing->discount_price == 0)
                @guest
                <div class="col-12 text-center">
                Ready to access these FREE materials? 
                <br><a href="{{ url($path.'/login') }}" class="btn btn-secondary">{{ __('LOGIN') }}</a> 
                <br>to your account now.
                @if (Route::has('register'))
                <hr>
                <br>Don't have an account yet?
                <br><a href="{{ url($path.'/register') }}" class="btn btn-secondary">{{ __('REGISTER') }}</a> 
                <br>for a free one now!
                @endif
                </div>
                @else
                <div class="col-12 text-center">
                Visit the 
                <br><a href="{{ url($path.'/dashboard/curricula/free-resources') }}" class="btn btn-secondary">FREE RESOURCES</a> 
                <br>page to download 
                <br>these materials now.
                </div>
                @endguest
            @endif
            <!-- if Purchasable Curriculum -->
            @if($defaultPricing->discount_price > 0)
            <div class="form-row">
                <div class="col-12 text-center pb-2">
                    <span class="package-caption">
                        {{ $defaultPricing->package_caption }}
                    </span>
                </div>
            </div>
            <div class="form-row">
                <div class="col">
                    <div class="mb-3 row justify-content-center">
                        <label for="qty1" class="col-2 col-form-label">Users</label>
                        <div class="col-3">
                        <input type="number" class="form-control package-count" rel="{{ $product->id }}" name="qty[{{ $product->id }}]" id="qty1" value="1" min="1">
                        </div>
                    </div>
                    <div class="col-12 mb-3 text-center">
                        1st year price per user $ <span class="discount-price">{{ number_format($defaultPricing->discount_price, 2) }}</span>
                        <br>Renewal price per user $ <span class="recurring-price">{{ number_format($defaultPricing->recurring_price, 2) }}</span>
                    </div>
                    <hr>
                    <div class="mb-3 row justify-content-center">
                        <div class="col-12 text-center">
                            <div class="form-group">
                                TOTAL $ {{number_format($defaultPricing->discount_price, 2)}}
                            </div>
                        </div>
                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-secondary add-cart">ADD TO CART</button>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </form>
        @else
        <div class="alert alert-warning">Pricing information not available for this curriculum.</div>
        @endif
    <!-- Purchase Training -->
    @elseif($product->category == 7)
        @if($vars->isNotEmpty())
        @php
            $trainingVar = $vars->first();
        @endphp
        <form action="{{ url($path.'/add_product_to_cart') }}" method="get" id="addForm">
            <input type="hidden" name="item_id" value="{{ $product->id }}" />
            <input type="hidden" name="var_id" value="{{ $trainingVar->var_id }}" />
            <div class="form-row">
                <div class="col-12">
                    <div class="form-group">
                        <b>{{ $product->name }}</b>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="qty">Participants</label>
                        <input type="number" class="form-control" id="qty" name="qty" placeholder="1" value="1" min="1">
                    </div>
                </div>
                <div class="col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="total">TOTAL</label>
                        <input type="text" class="form-control" id="total" value="$ {{ number_format($trainingVar->price, 2) }}" readonly>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <button type="submit" class="btn btn-secondary">ADD TO CART</button>
                    </div>
                </div>
            </div>
        </form>
        @else
        <div class="alert alert-warning">This training is currently unavailable.</div>
        @endif
    <!-- Purchase all other products (Books, Games, Activities, Toolkits, etc.) -->
    @else
        @if($vars->isNotEmpty())
        @php
            // $selectedVar is now calculated in parent product.blade.php and shared across all includes
            // Check if any variants have options assigned (needed for conditional rendering)
            $hasOptionsAssigned = !empty($variantAssignments);
        @endphp
        <form action="{{ url($path.'/add_product_to_cart') }}" method="get" id="addForm">
            <input type="hidden" name="item_id" value="{{ $product->id }}" />
            <input type="hidden" name="var_id" value="{{ $selectedVar->var_id }}" />
            
            @php
            @endphp
            
            <div class="form-row">
                {{-- UNIFIED BADGE SYSTEM FOR ALL PRODUCTS (except curriculum & training) --}}
                {{-- Case 1: Products with NO options - show variant name badges --}}
                @if(!$hasOptionsAssigned)
                    @if($vars->count() > 1)
                        {{-- Multiple variants without options - show variant name badges --}}
                        <div class="col-12">
                            <div class="form-group">
                                <label class="visually-hidden">Product Variants</label>
                                <div class="option-badges-container" id="variant-badges-container">
                                    @foreach($vars as $variant)
                                        <button type="button" 
                                                class="option-badge variant-badge {{ $variant->var_id == $selectedVar->var_id ? 'selected' : '' }}" 
                                                data-var-id="{{ $variant->var_id }}"
                                                data-price="{{ $variant->price }}"
                                                data-sku="{{ $variant->sku }}"
                                                data-description2="{{ htmlspecialchars($variant->description2 ?? '', ENT_QUOTES) }}"
                                                data-ship-type="{{ $variant->ship_type == 1 ? 'Physical' : 'Digital' }}">
                                            <span class="badge-label">{{ $variant->name ?? 'Standard' }}</span>
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @else
                        {{-- Single variant without options - show single selected badge with variant name --}}
                        <div class="col-12">
                            <div class="form-group">
                                <label class="visually-hidden">Product Variant</label>
                                <div class="option-badges-container">
                                    <button type="button" 
                                            class="option-badge variant-badge selected" 
                                            data-var-id="{{ $selectedVar->var_id }}"
                                            data-price="{{ $selectedVar->price }}"
                                            data-sku="{{ $selectedVar->sku }}"
                                            data-description2="{{ htmlspecialchars($selectedVar->description2 ?? '', ENT_QUOTES) }}"
                                            data-ship-type="{{ $selectedVar->ship_type == 1 ? 'Physical' : 'Digital' }}"
                                            data-single-variant="true"
                                            style="cursor: default;">
                                        <span class="badge-label">{{ $selectedVar->name ?? 'Standard' }}</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif

                {{-- UNIFIED OPTION BADGE SYSTEM --}}
                {{-- Case 2: Products WITH options - show option badges with availability tracking --}}
                @php
                    // Group option values by option name
                    $groupedOptions = [];
                    foreach($optionValues as $val) {
                        $optionId = $val->options_id;
                        if(!isset($groupedOptions[$optionId])) {
                            $groupedOptions[$optionId] = [
                                'name' => $val->option_name,
                                'values' => []
                            ];
                        }
                        $groupedOptions[$optionId]['values'][] = $val;
                    }
                    
                    // Build combinations map for all products with options
                    // Structure: $optionCombinations[option1ValueId][option2ValueId]... = varId
                    $optionCombinations = [];
                    $optionsList = array_keys($groupedOptions);
                    $firstVariantAssignments = $variantAssignments[$selectedVar->var_id] ?? [];
                    
                    foreach($variantAssignments as $varId => $assignedValues) {
                        // Build a key from all assigned option values for this variant
                        $comboKey = implode('_', $assignedValues);
                        $optionCombinations[$comboKey] = [
                            'varId' => $varId,
                            'values' => $assignedValues
                        ];
                    }
                    
                    // Check if multiple variants exist
                    $hasMultipleVariants = count($variantAssignments) > 1;
                @endphp

                @if($hasOptionsAssigned && $groupedOptions)
                    {{-- Render option badges for each option type --}}
                    @foreach($groupedOptions as $optionId => $option)
                        @php
                            // Get all value IDs for this option that are actually used
                            $optionValueIds = [];
                            foreach($variantAssignments as $varId => $assignedValues) {
                                foreach($assignedValues as $valId) {
                                    foreach($option['values'] as $val) {
                                        if($val->value_id == $valId && !in_array($valId, $optionValueIds)) {
                                            $optionValueIds[] = $valId;
                                        }
                                    }
                                }
                            }
                            
                            // Skip if no values for this option
                            if(empty($optionValueIds)) continue;
                            
                            // Get default selected value from first variant
                            $defaultValueId = null;
                            foreach($option['values'] as $val) {
                                if(in_array($val->value_id, $firstVariantAssignments)) {
                                    $defaultValueId = $val->value_id;
                                    break;
                                }
                            }
                            
                            // Filter and sort values
                            $sortedValues = collect($option['values'])->filter(function($val) use ($optionValueIds) {
                                return in_array($val->value_id, $optionValueIds);
                            });
                            
                            // Custom sort for sizes
                            if(stripos($option['name'], 'size') !== false) {
                                $sizeOrder = ['xs', 'sm', 's', 'med', 'm', 'lrg', 'l', 'xl', '2xl', '3xl', '4xl', '5xl'];
                                $sortedValues = $sortedValues->sort(function($a, $b) use ($sizeOrder) {
                                    $aName = strtolower(trim($a->name));
                                    $bName = strtolower(trim($b->name));
                                    $aIndex = array_search($aName, $sizeOrder);
                                    $bIndex = array_search($bName, $sizeOrder);
                                    if($aIndex !== false && $bIndex !== false) return $aIndex - $bIndex;
                                    if($aIndex !== false) return -1;
                                    if($bIndex !== false) return 1;
                                    return strcasecmp($aName, $bName);
                                });
                            } else {
                                $sortedValues = $sortedValues->sortBy('name', SORT_NATURAL|SORT_FLAG_CASE);
                            }
                            
                            // Calculate available combinations for each value
                            // For each value, find which OTHER option values are compatible
                            $availabilityMap = [];
                            foreach($sortedValues as $val) {
                                $availableWithThis = [];
                                foreach($variantAssignments as $varId => $assignedValues) {
                                    if(in_array($val->value_id, $assignedValues)) {
                                        // This variant has this option value
                                        // Store all OTHER option values from this variant
                                        foreach($assignedValues as $otherValId) {
                                            if($otherValId != $val->value_id && !in_array($otherValId, $availableWithThis)) {
                                                $availableWithThis[] = $otherValId;
                                            }
                                        }
                                    }
                                }
                                $availabilityMap[$val->value_id] = $availableWithThis;
                            }
                            
                            // Detect option type
                            $isColorOption = stripos($option['name'], 'color') !== false;
                            $isSizeOption = stripos($option['name'], 'size') !== false;
                        @endphp
                        
                        @if($sortedValues->count() > 0)
                            @if($hasMultipleVariants && $sortedValues->count() > 1)
                                {{-- Multiple values - show badges --}}
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="{{ $isColorOption || $isSizeOption ? 'visually-hidden' : '' }}">{{ $option['name'] }}</label>
                                        <div class="option-badges-container" data-option-id="{{ $optionId }}">
                                            @foreach($sortedValues as $val)
                                                @php
                                                    $badgeClass = $isColorOption ? 'color-badge' : ($isSizeOption ? 'size-badge' : 'generic-badge');
                                                    $isSelected = ($val->value_id == $defaultValueId);
                                                    $displayText = $isSizeOption ? strtoupper($val->name) : $val->name;
                                                    $sizeOrderAttr = '';
                                                    if($isSizeOption) {
                                                        $sizeOrderArray = ['xs', 'sm', 's', 'med', 'm', 'lrg', 'l', 'xl', '2xl', '3xl', '4xl', '5xl'];
                                                        $orderIndex = array_search(strtolower(trim($val->name)), $sizeOrderArray);
                                                        $sizeOrderAttr = $orderIndex !== false ? $orderIndex : 999;
                                                    }
                                                @endphp
                                                <button type="button" 
                                                        class="option-badge {{ $badgeClass }} {{ $isSelected ? 'selected' : '' }}" 
                                                        data-option-id="{{ $optionId }}"
                                                        data-value-id="{{ $val->value_id }}" 
                                                        data-value-name="{{ $val->name }}"
                                                        data-available-with='@json($availabilityMap[$val->value_id])'
                                                        @if($isSizeOption) data-size-order="{{ $sizeOrderAttr }}" @endif
                                                        @if($isColorOption) style="background-color: {{ strtolower($val->name) }};" @endif>
                                                    <span class="badge-label">{{ $displayText }}</span>
                                                    <span class="unavailable-overlay"></span>
                                                </button>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @else
                                {{-- Single value - always show as selected badge for unified UI --}}
                                @php
                                    $selectedValueName = $sortedValues->firstWhere('value_id', $defaultValueId)->name ?? $sortedValues->first()->name ?? '';
                                @endphp
                                @if($selectedValueName)
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="{{ $isColorOption || $isSizeOption ? 'visually-hidden' : '' }}">{{ $option['name'] }}</label>
                                            <div class="option-badges-container">
                                                @php
                                                    $badgeClass = $isColorOption ? 'color-badge' : ($isSizeOption ? 'size-badge' : 'generic-badge');
                                                    $displayText = $isSizeOption ? strtoupper($selectedValueName) : $selectedValueName;
                                                @endphp
                                                <button type="button" 
                                                        class="option-badge {{ $badgeClass }} selected" 
                                                        data-single-variant="true"
                                                        style="cursor: default; @if($isColorOption) background-color: {{ strtolower($selectedValueName) }}; @endif">
                                                    <span class="badge-label">{{ $displayText }}</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endif
                        @endif
                    @endforeach
                @endif {{-- End hasOptionsAssigned --}}

            </div>

            {{-- Store variant combinations in JSON for JavaScript --}}
            @if($hasOptionsAssigned)
            <script type="application/json" id="variantCombinations">
                @php
                    $jsonCombinations = [];
                    foreach($variantAssignments as $varId => $assignedValues) {
                        $variantData = $vars->firstWhere('var_id', $varId);
                        if($variantData) {
                            $combo = [
                                'varId' => $varId,
                                'price' => $variantData->price,
                                'sku' => $variantData->sku,
                                'description2' => $variantData->description2 ?? '',
                                'optionValues' => $assignedValues // Array of all option value IDs for this variant
                            ];
                            $jsonCombinations[] = $combo;
                        }
                    }
                    echo json_encode($jsonCombinations);
                @endphp
            </script>
            @endif

            {{-- Quantity and Total --}}
            <div class="form-row">
                {{-- Free Product Message (shown for free variants) --}}
                <div class="col-12 text-center" id="free-product-message" style="display: {{ $selectedVar->price == 0 ? 'block' : 'none' }};">
                    @guest
                    <p>Ready to access this FREE resource?</p>
                    <a href="{{ url($path.'/login') }}" class="btn btn-secondary">{{ __('LOGIN') }}</a>
                    <p class="mt-3">Don't have an account yet?</p>
                    <a href="{{ url($path.'/register') }}" class="btn btn-secondary">{{ __('REGISTER') }}</a>
                    <p class="mt-2">for a free one now!</p>
                    @else
                    <p>Visit the</p>
                    {{-- This link needs to change based on the free variant's delivery slug --}}
                    <a href="{{ url($path.'/dashboard/curricula/free-resources') }}" class="btn btn-secondary">FREE RESOURCES</a>
                    <p class="mt-2">page to download this resource now.</p>
                    @endguest
                </div>
                
                {{-- Paid Product Cart Interface (shown for paid variants) --}}
                <div class="col-md-6 col-sm-12" id="qty-field" style="display: {{ $selectedVar->price == 0 ? 'none' : 'block' }};">
                    <div class="form-group">
                        <label for="qty">QTY</label>
                        <input type="number" class="form-control" id="qty" name="qty" placeholder="1" value="1" min="1">
                    </div>
                </div>
                <div class="col-md-6 col-sm-12" id="total-field" style="display: {{ $selectedVar->price == 0 ? 'none' : 'block' }};">
                    <div class="form-group">
                        <label for="total">TOTAL</label>
                        <input type="text" class="form-control" id="total" value="$ {{ number_format($selectedVar->price, 2) }}" readonly>
                    </div>
                </div>
                <div class="col-12" id="add-cart-button" style="display: {{ $selectedVar->price == 0 ? 'none' : 'block' }};">
                    <div class="form-group">
                        <button type="button" class="btn btn-secondary add-cart">ADD TO CART</button>
                    </div>
                </div>
            </div>
        </form>
        @else
        <div class="alert alert-warning">This product is currently unavailable.</div>
        @endif
    @endif
</div>

@push('styles')
<style>
    /* Move dropdown caret to left side */
    #productVariation.form-select {
        background-position: left 0.75rem center;
        padding-left: 2.5rem;
        padding-right: 0.75rem;
    }
    
    /* Option Badges Container */
    .option-badges-container {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 2px;
    }
    
    /* Base Badge Style */
    .option-badge {
        position: relative;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 8px 12px;
        border-radius: 12px;
        border: 2px solid transparent;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
        background-color: #fff;
        color: #26598e;
        min-width: 60px;
        text-align: center;
    }
    
    /* Generic Badge for Other Options */
    .option-badge.generic-badge {
        background-color: #fff;
        border: 2px solid #dee2e6;
        color: #26598e;
    }
    
    /* Hover State */
    .option-badge:hover:not(.unavailable) {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }
    
    /* Selected State - Secondary Border */
    .option-badge.selected {
        border-color: var(--bs-secondary, #6c757d);
        border-width: 3px;
        box-shadow: 0 0 0 2px rgba(108, 117, 125, 0.2);
    }
    
    /* Unavailable State - Reduced Opacity */
    .option-badge.unavailable {
        opacity: 0.4;
        cursor: pointer; /* Still clickable */
    }
    
    /* Diagonal Line Overlay for Unavailable */
    .option-badge.unavailable .unavailable-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        pointer-events: none;
    }
    
    .option-badge.unavailable .unavailable-overlay::before {
        content: '';
        position: absolute;
        top: 50%;
        left: -5%;
        right: -5%;
        height: 2px;
        background-color: #dc3545;
        transform: translateY(-50%) rotate(-15deg);
        box-shadow: 0 0 3px rgba(0,0,0,0.3);
    }
    
    /* Responsive adjustments */
    @media (max-width: 576px) {
        .option-badge {
            padding: 8px 16px;
            font-size: 13px;
            min-width: 50px;
        }
        
        .option-badges-container {
            gap: 8px;
        }
    }

    .package-caption{
        font-weight: bold;
        font-size: 1rem;
    }
</style>
@endpush

@push('scripts')
{{-- Include unified product JavaScript handlers --}}
<script src="{{ asset('js/curriculum-product.js') }}"></script>
<script src="{{ asset('js/training-product.js') }}"></script>
<script src="{{ asset('js/unified-badge-system.js') }}"></script>
@endpush

