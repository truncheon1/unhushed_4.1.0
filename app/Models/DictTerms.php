<?php
namespace App\Models;
use App\Models\DictDefs;
use App\Models\DictImages;
use App\Models\DictTermsTags;
use App\Models\DictTags;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DictTerms extends Model
{
    use HasFactory;
    protected $fillable = [
        'term', 'phonetic', 'audio', 'slug', 'keyword'
    ];

    public function definitions() {
        return $this->hasMany('App\Models\DictDefs', 'term_id');
    }

    //set OrderBy('sort', 'ASC')-> in definitions function

    public function updateTerm(){
        $term = DictTerms::find(1);
        $term->touch();
    }
}
