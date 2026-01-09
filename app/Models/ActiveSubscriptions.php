<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActiveSubscriptions extends Model
{
    use HasFactory;

    // Status constants
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_CANCELED = 3;

    protected $fillable = [
        'user_id',
        'product_id',
        'category',
        'org_id',
        'total',
        'used',
        'status',
        'subscription_id',
        'quantity',
        'current_period_start',
        'current_period_end',
    ];

    protected $casts = [
        'current_period_start' => 'datetime',
        'current_period_end' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organizations::class, 'org_id');
    }

    public function product()
    {
        return $this->belongsTo(Products::class, 'product_id');
    }
}
