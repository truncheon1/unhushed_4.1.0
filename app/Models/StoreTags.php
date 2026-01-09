<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductTags extends Model
{
    use HasFactory;

    protected $table = 'product_tags';

    //refactor during type update
    const ACTIVITIES = 1;
    const BOOKS = 2;
    const GAMES = 3;
    const SWAG = 4;
    const TEACH = 5;
    const TRAINING = 6;

    function parent_tag(){
        return ProductTags::where('id', $this->parent_id)->first();
    }

    function root_tag(){
        return self::root_tags()[$this->root_id];
    }

    static function root_tags(){
        return [
            self::ACTIVITIES=>'Activities...',
            self::BOOKS=>'Books...',
            self::GAMES=>'Games...',
            self::SWAG=>'Swag...',
            self::TEACH=>'Teach...',
            self::TRAINING=>'Trainings...'
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
