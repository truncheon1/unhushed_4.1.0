@extends('layouts.app')
@section('content')
@include('layouts.storebar')
<section data-product-category="{{ $product->category }}">
    <!-- PAGE CONTENT -->
    <div class="containerStore">
        <!-- breadcrumbs -->
        <div class="crumbBar">
            <div class="breadcrumbsBack">
                <a href="{{ url($path.'/store') }}"> Store</a> |
                <a href="{{ url($path.$categoryInfo['slug']) }}"> {{ $categoryInfo['name'] }}</a> |
                <span style="font-weight: bold;color:#9acd57">{{ $product->name }}</span>
            </div>
        </div>
        
        @php
            // Calculate $selectedVar once for use across all included blade files
            if($vars->isNotEmpty()) {
                // Check if any variants have options assigned (needed for conditional rendering)
                $hasOptionsAssigned = !empty($variantAssignments);
                
                // Select initial variant: prefer first variant with options assigned, fallback to first available variant
                if($hasOptionsAssigned) {
                    // Get first variant from variantAssignments to ensure we select one with options
                    $firstVarIdWithOptions = array_key_first($variantAssignments);
                    $selectedVar = $vars->firstWhere('var_id', $firstVarIdWithOptions) ?? $vars->first();
                } else {
                    $selectedVar = $vars->first();
                }
            } else {
                $selectedVar = null;
            }
        @endphp
        
        <!-- PRODUCT 3 COLUMN LAYOUT -->
        <div class="row">
            <!-- Product Images - Desktop: Left, Mobile: First -->
            <div class="col-lg-4 col-md-5 col-12 product-images-col order-1 order-md-1">
                <div class="d-md-none">
                    @include('store.product.name')
                </div>
                @include('store.product.image')
            </div>
            
            <!-- Product Info - Desktop: Center, Mobile: Third -->
            <div class="col-lg-4 col-md-7 col-12 product-info-col order-3 order-md-2">
                <div class="d-none d-md-block">
                    @include('store.product.name')
                </div>
                @include('store.product.info')
            </div>
            
            <!-- Cart/Pricing - Desktop: Right, Mobile: Second -->
            <div class="col-lg-4 col-md-12 col-12 product-cart-col order-2 order-md-3">
                @include('store.product.price')
            </div>
        </div>

        <!-- PRODUCT DETAILS -->
        <div class="row justify-content-center px-2">
            <!-- Product Details - Desktop/Mobile: Fourth -->
            <div class="col-lg-8">
                @include('store.product.details')
            </div>
        </div>
        <!-- Policy row - Desktop/Mobile: Fifth-->
        <div class="row mt-5 text-center">
            <div class="col-12 pb-5">For information regarding our <a href="{{ url($path.'/purchases') }}">purchases and cancellations policies</a>.</div>
        </div>
    </div>

    <style>
        /* Product page layout fixes */
        .containerStore .row {
            margin: 0 -15px;
        }
        
        .product-images-col,
        .product-info-col,
        .product-cart-col {
            padding: 15px;
        }
        
        .product-cart-form {
            background: #f0f3fa;
            padding: 20px;
            border: 1px solid #d8dce7;
            border-radius: 6px;
        }
        
        /* Mobile fixes */
        @media (max-width: 767px) {
            .gallery {
                margin-top: 60px;
            }
            
            /* Fix text cut off on left side */
            .product-info-col {
                padding-left: 30px;
                padding-right: 30px;
            }
            
            .product-info-col .row {
                margin-left: 0;
                margin-right: 0;
            }
            
            .box-info {
                padding: 20px 0;
            }
        }
    </style>
</section>
@endsection
