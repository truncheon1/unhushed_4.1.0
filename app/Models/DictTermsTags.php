<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DictTermsTags extends Model
{
    use HasFactory;

    public function entry() {

        return $this->belongsTo('App\Models\Dictionaries');
    }
}
