<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Models\BlogComments;
use App\Models\BlogPosts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class CommentsController extends Controller
{
    public function add($path = 'educators', $id){
    }

    public function create(Request $request){
        $error = false;
        $message = '';
        $post_id = $request->input('post_id');
        if(!$post_id || !strlen(trim($post_id))){
            $error = true;
            $message = 'Post id was not understood.';
        }
        $comment = $request->input('comment');
        if(!$comment || !strlen(trim($comment))){
            $error = true;
            $message = 'A valid comment is required.';
        }
        //Input error
        if($error){
            return response()->json(['error'=>$error, 'message'=>$message]);
        }
        //Insert and return the post
        $entry = new BLogComments();
        $entry->user_id = Auth::user()->id;
        $entry->post_id = $request->input('post_id');
        $entry->comment = $request->input('comment');
        $entry->status = false;
        $entry->save();
        //Increase comment count by 1
        $count= BLogPosts::find($request->input('post_id'))->increment('comments');
        //Forget session
        $request->session()->forget('blog');
        return redirect()->back();
    }
}
