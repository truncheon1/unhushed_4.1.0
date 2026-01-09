<?php

namespace App\Models;

use Faker\Generator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;


class Products extends Model
{
    use HasFactory;

    public function migration(Blueprint $table){
        $table->id();
        $table->string('name', 191)->nullable();
        $table->string('category', 50)->nullable();
        $table->string('image')->nullable();
        $table->string('description', 500)->nullable();
        $table->decimal('price', 22)->nullable()->default(0.00);
        $table->decimal('qty', 3)->nullable()->default(000);
        $table->decimal('weight', 4)->nullable()->default(00.00);
        $table->timestamp('created_at')->useCurrent();
        $table->timestamp('updated_at')->nullable();
    }

    public static function definition(Generator $faker){
        return [
            'name' => $faker->name,
        ];
    }

    public static function rules(Products $products = null){
        return [
            'name' => ['required', Rule::unique('products')->ignore($products->id ?? null)],
        ];
    }

    public function ratings(){
        return $this->hasMany('App\Models\Ratings');
    }

    public function tags(){
        return $this->hasMany('\App\Models\ProductTags', 'product_id');
    }
    
    public function get_tags(){
        return \App\Models\ProductTags::where('product_id', $this->id)->get();
    }
    
    public function availability(){
        return \App\Models\ProductAvailability::where('product_id', $this->id)->first();
    }
    
    public function images(){
        return $this->hasMany('App\Models\ProductImages', 'product_id')->orderBy('sort', 'ASC');
    }
    
    public function descriptions(){
        return $this->hasMany('App\Models\ProductDescription', 'product_id')->orderBy('sort', 'ASC');
    }
    
    public function sizes(){
        // SwagSize table currently uses swag_id as the foreign key. Use that for now.
        // If a product_id column is added later for unified schema, consider a dynamic key.
        return $this->hasMany('App\Models\SwagSize', 'swag_id')->orderBy('size', 'ASC');
    }
    
    public function pricings(){
        return $this->hasMany('App\Models\BulkPricing', 'product_id')->orderBy('up_to', 'ASC');
    }
    
    public function get_price_for_range($num){
        // For non-curriculum products, just return the minimum available variant price
        // Bulk pricing was part of the old system - Stripe handles quantity-based pricing differently
        return $this->minAvailablePrice() ?? 0;
    }

    // New relationship to product variant rows (product_vars)
    public function vars()
    {
        return $this->hasMany(ProductVar::class, 'product_id');
    }

    // Alias for vars() - for compatibility
    public function variants()
    {
        return $this->vars();
    }

    // Relationship to curriculum pricing tiers
    public function curriculumPrices()
    {
        return $this->hasMany(CurriculumPrices::class, 'product_id');
    }

    // Convenience accessor for first / primary variant
    public function primaryVar()
    {
        return $this->vars()->orderBy('var_id','ASC')->first();
    }

    /**
     * Scope: only products that have at least one available variant (product_vars.avail = 1)
     * OR are category 1 (curriculum) with curriculum pricing data
     */
    public function scopeWithAvailableVariants($query)
    {
        return $query->where(function($q){
            // Products with at least one available variant
            $q->whereHas('vars', function($varQ){
                $varQ->where('avail', 1);
            })
            // OR category 1 products with curriculum pricing
            ->orWhere(function($currQ){
                $currQ->where('category', 1)
                      ->whereHas('curriculumPrices');
            });
        });
    }

    /**
     * Scope: eager load only available variants
     */
    public function scopeWithAvailableVarsOnly($query)
    {
        return $query->with(['vars' => function($q){
            $q->where('avail', 1);
        }]);
    }

    /**
     * DEPRECATED: Legacy product_availabilities table no longer used.
     * Availability is now controlled solely by product_vars.avail column.
     * This method is kept for reference but does nothing.
     */
    public function scopeApplyLegacyAvailability($query)
    {
        // No-op: product_availabilities table removed from system
        return $query;
    }

    /**
     * Composite scope: products with at least one available variant.
     * Legacy availability check removed - product_availabilities table no longer used.
     */
    public function scopeAvailable($query)
    {
        return $query->withAvailableVariants();
    }

    /**
     * Instance: does product have any available variants or curriculum pricing?
     */
    public function hasAvailableVariants()
    {
        // Check for available variants
        if($this->vars()->where('avail',1)->exists()){
            return true;
        }
        // For category 1, also check curriculum pricing
        if($this->category == 1 && $this->curriculumPrices()->exists()){
            return true;
        }
        return false;
    }

    /**
     * Instance: first available variant (ordered by var_id ASC)
     */
    public function firstAvailableVar()
    {
        return $this->vars()->where('avail',1)->orderBy('var_id','ASC')->first();
    }

    /**
     * Instance: collection of all available variants
     */
    public function availableVars()
    {
        return $this->vars()->where('avail',1)->orderBy('var_id','ASC')->get();
    }

    /**
     * Instance: minimum price across available variants or curriculum pricing (fallback to product price)
     */
    public function minAvailablePrice()
    {
        // For curriculum products (category 1), check curriculum_prices table
        if($this->category == 1){
            $curriculumMin = $this->curriculumPrices()->min('discount_price');
            if($curriculumMin !== null){
                return $curriculumMin;
            }
        }
        
        // For all other products, check variants
        $min = $this->vars()->where('avail',1)->min('price');
        return $min !== null ? $min : $this->price;
    }

    /**
     * Accessor: formatted display price based on lowest available variant price.
     * Returns 'FREE' if < $0.01.
     */
    public function getDisplayPriceAttribute()
    {
        $min = $this->minAvailablePrice();
        if($min === null || $min < 0.01){
            return 'FREE';
        }
        return '$ '.number_format($min,2);
    }

    /**
     * Accessor: primary_image_path
     * Returns image file name for product's primary (sort=1) image, or falls back to legacy products.image
     * Uses already eager-loaded images relation if present to avoid extra query.
     */
    public function getPrimaryImagePathAttribute()
    {
        // If relation is eager-loaded use it, otherwise query.
        if($this->relationLoaded('images')){
            // Prefer explicit primary (sort=1)
            $primary = $this->images->first(function($img){ return (int)$img->sort === 1; });
            // If no sort=1 image exists, fallback to first by sort asc
            if(!$primary){
                $primary = $this->images->sortBy(function($img){ return (int)$img->sort; })->first();
            }
            if($primary && $primary->image){
                return $primary->image;
            }
        } else {
            // Query primary; if not found query first image
            $primary = $this->images()->where('sort',1)->first();
            if(!$primary){
                $primary = $this->images()->orderBy('sort','ASC')->first();
            }
            if($primary && $primary->image){
                return $primary->image;
            }
        }
        // Legacy single image column fallback or placeholder
        return $this->image ?: 'placeholder.png';
    }
}
