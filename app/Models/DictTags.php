<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DictTags extends Model
{
    use HasFactory;

    const GENDER = 1;
    const ORIENTATION = 2;
    const MEDICAL = 3;
    const FACILITATION = 4;
    const SLANG = 5;

    function parent_tag(){
        return DictTags::where('id', $this->parent_id)->first();
    }

    function root_tag(){
        return self::root_tags()[$this->root_id];
    }

    static function root_tags(){
        return [
            self::GENDER        => 'Gender...',
            self::ORIENTATION   => 'Orientation...',
            self::MEDICAL       => 'Medical...',
            self::FACILITATION  => 'Facilitation...',
            self::SLANG         => 'Slang...',
        ];
    }

    function parent_tag_name(){
        $tag = $this->parent_tag();
        if($tag){
            return $tag->name;
        }
        return '-';
    }
}
