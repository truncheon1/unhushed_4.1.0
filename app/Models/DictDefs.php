<?php

namespace App\Models;
use App\Models\DictDefs;
use App\Models\DictImages;
use App\Models\DictTerms;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DictDefs extends Model
{
    use HasFactory;
    protected $fillable = [
        'term_id', 'part', 'sort', 'def', 'example'
    ];

    //Get definitions for term
    public function images(): HasMany{
        return $this->hasMany(DictImages::class, 'def_id');
    }

    public function term() {
        return $this->belongsTo(DictTerms::class);
    }
}
