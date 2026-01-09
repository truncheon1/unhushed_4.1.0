<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SwagSize extends Model
{
    use HasFactory;

    // Actual column in schema is swag_id; include both for potential forward compatibility
    protected $fillable = ['swag_id', 'size', 'price', 'min_donation', 'qty', 'weight'];

    public function product() {
        // Relate via swag_id since products now represent swag items
        return $this->belongsTo('App\Models\Products', 'swag_id');
    }

    // Legacy relation name
    public function swag() {
        return $this->belongsTo('App\Models\Swag', 'swag_id');
    }
}
