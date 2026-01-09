<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchaseFulfillment extends Model
{
    use HasFactory;

    protected $table = 'purchase_fulfillments';

    // Fulfillment status constants
    const STATUS_ORDERED = 0;    // Default for all non-digital products
    const STATUS_SHIPPED = 1;    // Physical products shipped
    const STATUS_DIGITAL = 2;    // Digital products delivered
    const STATUS_BACKORDER = 3;  // Products on backorder
    const STATUS_CANCELED = 4;   // Canceled or refunded order

    protected $fillable = [
        'cart_id',
        'address_id',
        'status',
        'shipped',
        'tracking',
        'notes',
    ];

    protected $casts = [
        'shipped' => 'datetime',
        'status' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the cart this fulfillment belongs to
     */
    public function cart(): BelongsTo
    {
        return $this->belongsTo(PurchaseCart::class, 'cart_id');
    }

    /**
     * Get the shipping address for this fulfillment
     */
    public function address(): BelongsTo
    {
        return $this->belongsTo(UserAddress::class, 'address_id');
    }

    /**
     * Check if fulfillment has tracking information
     */
    public function hasTracking(): bool
    {
        return !empty($this->tracking);
    }

    /**
     * Check if tracking is numeric (USPS)
     */
    public function isNumericTracking(): bool
    {
        return is_numeric($this->tracking);
    }

    /**
     * Check if order has been shipped
     */
    public function isShipped(): bool
    {
        return $this->status === self::STATUS_SHIPPED;
    }

    /**
     * Check if order is digital
     */
    public function isDigital(): bool
    {
        return $this->status === self::STATUS_DIGITAL;
    }

    /**
     * Check if order is on backorder
     */
    public function isBackorder(): bool
    {
        return $this->status === self::STATUS_BACKORDER;
    }

    /**
     * Check if order is canceled
     */
    public function isCanceled(): bool
    {
        return $this->status === self::STATUS_CANCELED;
    }
}
