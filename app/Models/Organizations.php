<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organizations extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'city',
        'state',
        'zip',
        'country',
        'website',
        'phone',
        'email',
        'tax_exempt',
        'tax_exempt_id',
        'tax_exempt_type',
        'tax_exempt_certificate',
        'tax_exempt_expiry',
        'tax_exempt_verified_at',
        'tax_exempt_verified_by',
    ];

    protected $casts = [
        'tax_exempt' => 'boolean',
        'tax_exempt_expiry' => 'date',
        'tax_exempt_verified_at' => 'datetime',
    ];

    // Legacy relationship name - returns ActiveSubscriptions for this organization
    public function packages(){
        return $this->hasMany(ActiveSubscriptions::class, 'org_id');
    }

    // More descriptive alias for packages() relationship
    public function activeSubscriptions(){
        return $this->hasMany(ActiveSubscriptions::class, 'org_id');
    }

    public function head(){
        return $this->belongsTo('App\Models\User', 'head_id');
    }

    public function verifiedBy(){
        return $this->belongsTo('App\Models\User', 'tax_exempt_verified_by');
    }
}
