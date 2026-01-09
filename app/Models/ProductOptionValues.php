<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductOptionValues extends Model
{
    use HasFactory;

    protected $table = 'product_options_values';
    protected $primaryKey = 'value_id';
    public $timestamps = true; // adjust to false if table doesn't track timestamps

    protected $fillable = [
        'options_id', // FK to product_options.option_id
        'name',
        // add other columns here if present (e.g., sort)
    ];

    public function option()
    {
        // product_options has primary key option_id
        return $this->belongsTo(ProductOptions::class, 'options_id', 'option_id');
    }
}
