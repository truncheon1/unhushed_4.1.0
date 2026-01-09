<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVar extends Model
{
    use HasFactory;

    protected $table = 'product_vars';
    protected $primaryKey = 'var_id';
    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = [
        'product_id','name','sku','deliver_slug','template','price','taxable','ship_type','weight_lbs','weight_oz','qty','avail','sort'
    ];

    /**
     * Get the primary key for the model.
     */
    public function getKeyName()
    {
        return 'var_id';
    }

    public function product()
    {
        return $this->belongsTo(Products::class, 'product_id');
    }
}
