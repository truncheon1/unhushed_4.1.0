<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogPosts extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'content', 'author', 'slug', 'tags', 'description', 'image', 'user_id', 'status' ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function category(){
        return $this->belongsTo('App\BlogCategories');
    }

    public function tags(){
        return $this->belongsTo('App\BlogTags');
    }

    public function comments(){
        return $this->hasMany('App\BlogComments');
    }

    public function views()
    {
        return $this->hasMany(BlogViews::class);
    }


}

