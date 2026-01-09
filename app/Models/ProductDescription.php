<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductDescription extends Model
{
    use HasFactory;

    protected $table = 'product_descriptions';

    protected $fillable = [
        'product_id',
        'variant_ids',
        'sort',
        'description',
    ];

    protected $casts = [
        'variant_ids' => 'array',
    ];

    public function product()
    {
        return $this->belongsTo(Products::class, 'product_id');
    }
}
