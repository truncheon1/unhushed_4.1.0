<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurriculumPrices extends Model
{
    use HasFactory;

    protected $table = 'curriculum_prices';

    protected $fillable = [
        'product_id',
        'min_users',
        'max_users',
        'standard_price',
        'discount_price',
        'recurring_price',
        'package_caption',
    ];

    protected $casts = [
        'product_id'      => 'integer',
        'min_users'       => 'integer',
        'max_users'       => 'integer',
        'standard_price'  => 'float',
        'discount_price'  => 'float',
        'recurring_price' => 'float',
    ];

    public function product()
    {
        return $this->belongsTo(Products::class, 'product_id');
    }

    public function scopeForUsers($query, int $num)
    {
        return $query->where('min_users', '<=', $num)->where('max_users', '>=', $num);
    }
}
