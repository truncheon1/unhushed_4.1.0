<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserAddress extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'user_id',
        'default',
        'name',
        'company',
        'email',
        'phone',
        'street',
        'city',
        'zip',
        'county',
        'state_province',
        'country',
    ];

    protected $casts = [
        'default' => 'boolean',
    ];

    /**
     * Get the user that owns the address
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope to get default address for a user
     */
    public function scopeDefault($query, $userId)
    {
        return $query->where('user_id', $userId)->where('default', true);
    }

    /**
     * Scope addresses for a specific user
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Set this address as the default and unset others
     */
    public function setAsDefault()
    {
        // Unset all other default addresses for this user
        self::where('user_id', $this->user_id)
            ->where('id', '!=', $this->id)
            ->update(['default' => false]);
        
        // Set this one as default
        $this->default = true;
        $this->save();
    }

    /**
     * Get full address as string
     */
    public function getFullAddressAttribute()
    {
        $parts = array_filter([
            $this->street,
            $this->city,
            $this->state_province,
            $this->zip,
            $this->country !== 'US' ? $this->country : null,
        ]);

        return implode(', ', $parts);
    }
}
