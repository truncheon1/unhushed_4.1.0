<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductOptions extends Model
{
    use HasFactory;

    protected $table = 'product_options';
    protected $primaryKey = 'option_id';
    protected $fillable = ['name', 'categories'];
    protected $casts = [
        'categories' => 'array'
    ];
}
