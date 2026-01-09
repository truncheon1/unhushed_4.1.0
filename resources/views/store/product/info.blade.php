<!-- PRODUCT INFORMATION TEXT -->
    <!-- PRICE -->
    @if($hasPricing && $displayPrice > 0)
    <div class="row">
        <div class="col-2 info">PRICE</div>
        <div class="col-10">
            <b>$ <span id="displayPrice">{{ number_format($displayPrice, 2) }}</span></b>
        </div>
    </div>
    @endif

    <!-- DESCRIPTION -->
    <div class="row">
        <div class="col-12"><hr></div>
        <div class="col-12" id="product-descriptions-container" style="text-align:justify;">
            @if($descriptions && $descriptions->count() > 0)
                @foreach($descriptions as $description)
                    <?php
                        // Show by default if no variant_ids (assigned to all) OR if product has no variants (curriculum)
                        $hasNoVariants = $vars->isEmpty();
                        $variantIds = $description->variant_ids;
                        // Handle both array and JSON string
                        if (is_string($variantIds)) {
                            $variantIds = json_decode($variantIds, true);
                        }
                        $assignedToAll = !$variantIds || (is_array($variantIds) && count($variantIds) == 0);
                        $showByDefault = $assignedToAll || $hasNoVariants;
                    ?>
                    <div class="product-description" 
                         data-description-id="{{ $description->id }}" 
                         data-variant-ids='@json($description->variant_ids)'
                         style="{{ $showByDefault ? '' : 'display:none;' }}">
                        {!! $description->description !!}
                    </div>
                @endforeach
            @else
                <p class="text-muted">No description available.</p>
            @endif
        </div>
    </div>
    
    <!-- Remove old variant description container (now handled above) -->

<!-- activity/curriculum alignment row -->
@if($product->category == 1)
<div class="row pb-1">
    <div class="col-12"><hr></div>
    <div class="col-12">
        Learn more about educational <a href="{{ url($path.'/about#standards') }}" target="_blank">standards</a> this curriculum aligns with here.
    </div>
</div>
@endif