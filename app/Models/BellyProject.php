<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BellyProject extends Model
{
    use HasFactory;
    protected $table = 'belly_project';

    public function images() {
        return $this->hasMany('App\Models\BellyImages', 'id')->orderBy('sort', 'ASC');
    }

}
