<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseItem extends Model
{
    use HasFactory;

    // Legacy type constants for backward compatibility
    // Note: purchase_items table uses product_id + var_id instead of item_type
    const TYPE_PRODUCT = 0;
    const TYPE_ACTIVITYD = 1;
    const TYPE_ACTIVITYP = 2;
    const TYPE_BOOKD = 3;
    const TYPE_BOOKP = 4;
    const TYPE_CURRICULUM = 5;
    const TYPE_GAME = 6;
    const TYPE_SWAG = 7;
    const TYPE_TOOL = 8;
    const TYPE_TRAINING = 9;

    protected $table = 'purchase_items';

    protected $fillable = [
        'cart_id',
        'product_id',
        'var_id',
        'quantity',
        'price',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'quantity' => 'integer',
    ];

    /**
     * Get the cart this item belongs to
     */
    public function cart()
    {
        return $this->belongsTo(PurchaseCart::class, 'cart_id');
    }

    /**
     * Get the product
     */
    public function product()
    {
        return $this->belongsTo(Products::class, 'product_id');
    }

    /**
     * Get the product variant
     */
    public function variant()
    {
        return $this->belongsTo(ProductVar::class, 'var_id');
    }

    /**
     * Note: The 'price' field stores the line total (unit price × quantity)
     * For historical consistency with the system
     * Use $item->price to get line total, or $item->price / $item->quantity for unit price
     */
}
