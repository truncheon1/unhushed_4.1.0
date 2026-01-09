<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Models\BlogComments;
use App\Models\BlogPosts;
use Illuminate\Http\Request;


class LikesController extends Controller
{
    public function update(Request $request){
        $error = false;
        $message = '';
        $post_id = $request->input('post_id');
        if(!$post_id || !strlen(trim($post_id))){
            $error = true;
            $message = 'Post id was not understood.';
        }
        //Input error
        if($error){
            return response()->json(['error'=>$error, 'message'=>$message]);
        }
        //Increase like count by 1
        $count= BLogPosts::find($request->input('post_id'))->increment('likes');
        return redirect()->back();
    }
}
