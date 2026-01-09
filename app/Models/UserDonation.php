<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserDonation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'payment_id',
        'subscription_id',
        'amount',
        'recurring',
        'status',
        'payment_method_id',
        'payment_method',
        'message',
        'reference_number',
        'notes',
        'receipt_sent',
        'receipt_sent_at',
        'completed_at'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'recurring' => 'boolean',
        'receipt_sent' => 'boolean',
        'receipt_sent_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';
    const STATUS_REFUNDED = 'refunded';

    /**
     * Relationship to User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope: Completed donations only
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    /**
     * Scope: Recurring donations
     */
    public function scopeRecurring($query)
    {
        return $query->where('recurring', true);
    }

    /**
     * Scope: One-time donations
     */
    public function scopeOneTime($query)
    {
        return $query->where('recurring', false);
    }

    /**
     * Scope: Anonymous donations
     */
    public function scopeAnonymous($query)
    {
        return $query->where('user_id', 0);
    }

    /**
     * Scope: Non-anonymous donations
     */
    public function scopeNamed($query)
    {
        return $query->where('user_id', '>', 0);
    }

    /**
     * Check if donation is anonymous
     */
    public function isAnonymous()
    {
        return $this->user_id == 0;
    }

    /**
     * Get donor name (or Anonymous)
     */
    public function getDonorNameAttribute()
    {
        if ($this->isAnonymous()) {
            return 'Anonymous';
        }
        return $this->user ? $this->user->name : 'Unknown';
    }

    /**
     * Get formatted amount
     */
    public function getFormattedAmountAttribute()
    {
        return '$' . number_format($this->amount, 2);
    }

    /**
     * Get donation type label
     */
    public function getTypeLabel()
    {
        return $this->recurring ? 'Monthly Recurring' : 'One-time';
    }

    /**
     * Get status badge color
     */
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            self::STATUS_COMPLETED => 'success',
            self::STATUS_PENDING => 'warning',
            self::STATUS_FAILED => 'danger',
            self::STATUS_REFUNDED => 'secondary',
            default => 'info'
        };
    }

    /**
     * Mark as completed
     */
    public function markCompleted()
    {
        $this->status = self::STATUS_COMPLETED;
        $this->completed_at = now();
        $this->save();
    }

    /**
     * Mark receipt as sent
     */
    public function markReceiptSent()
    {
        $this->receipt_sent = true;
        $this->receipt_sent_at = now();
        $this->save();
    }
}
