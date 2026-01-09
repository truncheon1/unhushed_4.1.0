<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurriculumPrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'min_users',
        'max_users',
        'standard_price',
        'discount_price',
        'recurring_price',
        'stripe_price_id',
        'package_caption',
    ];

    protected $casts = [
        'min_users' => 'integer',
        'max_users' => 'integer',
        'standard_price' => 'float',
        'discount_price' => 'float',
        'recurring_price' => 'float',
    ];

    /**
     * Get the product this pricing tier belongs to
     */
    public function product()
    {
        return $this->belongsTo(Products::class, 'product_id');
    }

    /**
     * Get pricing option for a given user count
     * 
     * @param int $userCount Number of users
     * @return CurriculumPrice|null
     */
    public static function getOptionForRange($productId, $userCount)
    {
        return self::where('product_id', $productId)
            ->where('min_users', '<=', $userCount)
            ->where('max_users', '>=', $userCount)
            ->first();
    }
}
