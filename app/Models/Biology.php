<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Biology extends Model
{
    use HasFactory;
    protected $fillable = [
        'term', 'chromosomes', 'hormones', 'gonads', 'external', 'internal'
    ];
}
