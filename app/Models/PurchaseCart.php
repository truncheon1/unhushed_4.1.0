<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseCart extends Model
{
    use HasFactory;

    protected $table = 'purchase_carts';

    // Cart status constants
    const CART_OPEN = 0;      // Active shopping
    const CART_ORDERED = 1;   // Payment submitted
    const CART_COMPLETE = 2;  // Fulfilled
    const CART_CANCELED = 3;  // Abandoned

    protected $fillable = [
        'payment_id',
        'user_id',
        'completed',
        'tax',
        'shipping',
        'total',
        'stripe_payment_intent_id',
        'completed_at',
    ];

    protected $casts = [
        'tax' => 'decimal:2',
        'shipping' => 'decimal:2',
        'total' => 'decimal:2',
        'completed_at' => 'datetime',
    ];

    /**
     * Get the user who owns this cart
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the items in this cart
     */
    public function items()
    {
        return $this->hasMany(PurchaseItem::class, 'cart_id');
    }

    /**
     * Get the fulfillment record for this cart
     */
    public function fulfillment()
    {
        return $this->hasOne(PurchaseFulfillment::class, 'cart_id');
    }

    /**
     * Check if cart is completed
     */
    public function isCompleted()
    {
        return $this->completed === self::CART_COMPLETE;
    }

    /**
     * Check if cart is ordered (payment submitted but not fulfilled)
     */
    public function isOrdered()
    {
        return $this->completed === self::CART_ORDERED;
    }

    /**
     * Check if cart is open (still shopping)
     */
    public function isOpen()
    {
        return $this->completed === self::CART_OPEN;
    }

    /**
     * Check if cart is canceled
     */
    public function isCanceled()
    {
        return $this->completed === self::CART_CANCELED;
    }
}
