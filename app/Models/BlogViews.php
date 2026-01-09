<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class BlogViews extends Model
{
    use HasFactory;
    protected $table = 'blog_views';

    public static function createViewLog($post) {
        $blogViews = new BlogViews();
        $blogViews->post_id = $post->id;
        $blogViews->slug = $post->slug;
        $blogViews->url = request()->fullUrl();
        $blogViews->session_id = session()->getId();
        $blogViews->user_id = (Auth::check())?Auth::id():null;
        $blogViews->ip = request()->ip();
        $blogViews->agent = request()->server('HTTP_USER_AGENT');
        $blogViews->save();
    }
}
