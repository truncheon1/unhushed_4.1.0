<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAssignments extends Model
{
    use HasFactory;

    // Active status constants
    const INACTIVE = 0;
    const ACTIVE = 1;

    protected $table = 'product_assignments';

    protected $fillable = [
        'user_id', 'product_id', 'var_id', 'category', 'active', 'status'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function product() {
        return $this->belongsTo(Products::class, 'product_id');
    }

    public function variant() {
        return $this->belongsTo(ProductVar::class, 'var_id', 'var_id');
    }

    public function package(){
        return $this->belongsTo(Products::class, 'product_id');
    }

    // Backward compatibility attribute shims
    public function getPackageIdAttribute()
    {
        return $this->attributes['product_id'] ?? null;
    }

    public function setPackageIdAttribute($value)
    {
        $this->attributes['product_id'] = $value;
    }

    public function getTypeAttribute()
    {
        return $this->attributes['category'] ?? null;
    }

    public function setTypeAttribute($value)
    {
        $this->attributes['category'] = $value;
    }

    // =====================================================
    // CURRICULUM-SPECIFIC STATIC METHODS
    // =====================================================

    /**
     * Check if a user has access to a specific curriculum (product with category=1).
     * 
     * Rules:
     *  - Admin or Team roles always true
     *  - If product matches configured free curriculum id and free_requires_assignment = false, allow
     *  - Otherwise require active ProductAssignments (category = 1) row
     * 
     * @param int $userId
     * @param int $productId
     * @return bool
     */
    public static function userHasCurriculum(int $userId, int $productId): bool
    {
        if ($userId <= 0 || $productId <= 0) return false;
        $user = User::find($userId);
        if (!$user) return false;
        if ($user->hasRole('admin','team')) return true;

        $freeId = config('curricula.ids.free');
        $freePolicy = (bool) config('curricula.free_requires_assignment', false);
        if (!$freePolicy && $freeId && $productId === (int)$freeId) {
            return true; // free curriculum open to all authenticated users
        }

        return self::where('user_id', $userId)
            ->where('product_id', $productId)
            ->where('category', 1)
            ->where('active', 1)
            ->exists();
    }

    /**
     * Fetch a curriculum product by exact name with images eager loaded
     */
    public static function curriculumByName(string $name): ?Products
    {
        return Products::with('images')->where('category', 1)->where('name', $name)->first();
    }

    /**
     * Convenience: Elementary Curriculum product
     */
    public static function elementary(): ?Products
    {
        $name = config('curricula.names.elementary', 'Elementary School Curriculum');
        return self::curriculumByName($name);
    }

    /**
     * Convenience: Middle School Curriculum product
     */
    public static function middle(): ?Products
    {
        $name = config('curricula.names.middle', 'Middle School Curriculum');
        return self::curriculumByName($name);
    }

    /**
     * Convenience: High School Curriculum product
     */
    public static function high(): ?Products
    {
        $name = config('curricula.names.high', 'High School Curriculum');
        return self::curriculumByName($name);
    }

    /**
     * Convenience: Free Curriculum Resources product
     */
    public static function free(): ?Products
    {
        $name = config('curricula.names.free', 'Free Curriculum Resources');
        return self::curriculumByName($name);
    }

    /**
     * Fetch units for given curriculum product (by product id or instance)
     */
    public static function unitsFor($product)
    {
        $productId = is_numeric($product) ? (int)$product : ($product->id ?? 0);
        if(!$productId){
            return collect();
        }
        return CurriculumUnits::where('product_id', $productId)
            ->orderBy('number','ASC')
            ->get();
    }
}

