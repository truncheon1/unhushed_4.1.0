<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductFiles extends Model
{
    use HasFactory;
    
    protected $casts = [
        'variant_ids' => 'array',
    ];
    
    public function product(){
        return $this->belongsTo('App\Models\Products');
    }
}
