<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discounts extends Model
{
    use HasFactory;
    
    const DISCOUNT_AVAILABLE = 1;
    const DISCOUNT_APPLIED = 2;
    const DISCOUNT_EXPIRED = 3;
    
    const TYPE_FIXED = 0;
    const TYPE_PERCENTAGE = 1;
    
    
}
