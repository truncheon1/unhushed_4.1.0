<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Swag extends Model
{
    use HasFactory;

    public function images() {
        return $this->hasMany('App\Models\ProductImages', 'swag_id')->orderBy('sort', 'ASC');
    }

    public function sizes() {
        return $this->hasMany('App\Models\SwagSize', 'swag_id');
    }
}
