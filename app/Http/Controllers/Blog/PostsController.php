<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Models\BlogComments;
use App\Models\BlogPosts;
use App\Models\BlogViews;
use App\Models\User;
use App\Models\UserPermissions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostsController extends Controller
{
    //Public blog view
    public function blog($path = 'educators'){
        return view('more.blog.blog')
        ->with('posts', BLogPosts::orderBy('updated_at', 'DESC')->get())
        ->with('path', get_path($path));
    }
    public function view_post($path = 'educators', $slug){
        //get post
        $post = BlogPosts::where('slug', $slug)->first();
        //count post views
        $post->increment('views');
        BlogViews::createViewLog($post);
        //get comments
        $entries = [];
        $data1 = BlogComments::where('post_id', $post->id)->get();
        foreach($data1 as $entry){
            $entries[] = [
                'name'   => User::where('id', $entry->user_id)->first()->name,
                'comment'   => $entry->comment,
                'avatar' => User::where('id', $entry->user_id)->first()->avatar,
            ];
        }
        //add session
        //if(($training->slug == 'bonus-tech-session'))
        //    session(['tech'=> true]);
        //return view
        return view('more.blog.post', compact('entries'))
            ->with('post', BlogPosts::where('slug', $slug)->first())
            ->with('path', get_path($path));
    }

    //BLOG CRUD

    // //This is old
    // const IMAGE_UPLOADS = '/public_html/uploads/blog';
    // const IMAGE_UPLOADS_TEMP = '/public_html/uploads/avatars/temp';
    // const PUBLIC_PATH = '/uploads/blog/';
    // const PUBLIC_PATH_TEMP = '/uploads/avatars/temp';

    // public function __construct() {
    //     if(!is_dir(base_path() .self::IMAGE_UPLOADS)){
    //         mkdir(base_path() . self::IMAGE_UPLOADS);
    //     }
    //     if(!is_dir(base_path() .self::IMAGE_UPLOADS_TEMP)){
    //         mkdir(base_path() . self::IMAGE_UPLOADS_TEMP);
    //     }
    // }

    // //This is the new way
    // if(is_file(config('constant.hold').'/'.$img)){
    //     copy(config('constant.hold').'/'.$img,
    //     config('constant.hold').'/'.$img);
    //     $image = new ProductImages();
    //     $image->image = $img;
    //     $image->sort = $sort;
    //     $image->product_id = $collection->id;
    //     $image->save();
    //     $sort++;
    //     if($save_img == ''){
    //         $save_img = $img;
    //     }
    // }

    public function posts($path = 'educators'){
        return view('backend.blog.posts')
        ->with('posts', BLogPosts::orderBy('updated_at', 'DESC')->get())
        ->with('path', get_path($path));
    }

    public function add($path = 'educators'){
        $users = UserPermissions::where('permission_id', 4)->get();
        $authors = [];
        foreach($users as $user){
            $userdata = User::where('id', $user->user_id)->first();
            $authors[] = [
                'id'   => $userdata->id,
                'name' => $userdata->name,
            ];
        }
        return view('backend.blog.create', compact('authors'))
        ->with('path', get_path($path));
    }

    public function create(Request $request){
        $error = false;
        $message = '';
        $description = $request->input('description');
        if(!$description || !strlen(trim($description))){
            $error = true;
            $message = 'Post needs a description.';
        }
        $tags = $request->input('tags');
        if(!$tags || !strlen(trim($tags))){
            $error = true;
            $message = 'At least one tag is required.';
        }
        $slug = $request->input('slug');
        if(!$slug || !strlen(trim($slug))){
            $error = true;
            $message = 'Slug is required.';
        }
        $user_id = $request->input('user_id');
        if(!$user_id || !strlen(trim($user_id))){
            $error = true;
            $message = 'Author is required.';
        }
        $status = $request->input('status');
        if(!$status || !strlen(trim($status))){
            $error = true;
            $message = 'Status is required.';
        }
        $content = $request->input('content');
        if(!$content || !strlen(trim($content))){
            $error = true;
            $message = 'Post needs content.';
        }
        $title = $request->input('title');
        if(!$title || !strlen(trim($title))){
            $error = true;
            $message = 'Post needs a title.';
        }
        //Input error
        if($error){
            return response()->json(['error'=>$error, 'message'=>$message]);
        }
        $post = BLogPosts::where('title', trim($title))->first();
        if($post){
            return response()->json(['error'=>true, 'message'=>'A post with the same name already exists.']);
        }
        //Insert and return the post
        $post = new BLogPosts();
        if($request->input('file')){
            //copy image from temp
            if(is_file(base_path(). self::IMAGE_UPLOADS_TEMP.'/'.$request->input('file'))){
                copy(   base_path(). self::IMAGE_UPLOADS_TEMP.'/'.$request->input('file'),
                        base_path(). self::IMAGE_UPLOADS.'/'.$request->input('file'));
            }
            //remove temp files
            $temp_folder = base_path(). self::IMAGE_UPLOADS_TEMP;
            $files = glob($temp_folder.'/*');
            foreach($files as $file) {
                if(is_file($file))
                    // Delete the given file
                    unlink($file);
            }
            $post->image = $request->input('file');
        }else{
            return response()->json(['error'=>true, 'message'=>'Image is required']);
        }
        $post->title = trim($title);
        $post->content = $request->input('content');
        $post->user_id = $request->input('user_id');
        $post->slug = $request->input('slug');
        $post->tags = $request->input('tags');
        $post->description = $request->input('description');
        $post->status = $request->input('status');
        $post->comments = 0;
        $post->views = 0;
        $post->likes = 0;
        $post->save();
        return response()->json(['success'=>true, 'post'=>$post]);
    }

    public function view($id){
        $post = BlogPosts::find($id);
        if(!$post){
            return response()->json(['success'=>false, 'message'=>'Invalid ID']);
        }
        return response()->json(['success'=>true, 'product'=>$post]);
    }

    public function show($path = 'educators', $id){
        $authors = [];
        $post = BlogPosts::find($id);
        if(!$post){
            abort(404);
        }
        return view('backend.blog.edit', compact('authors'))
        ->with('post', $post)
        ->with('section', 'backend')
        ->with('path', get_path($path));
    }

    public function update(Request $request){
        $error = false;
        $message = '';
        $description = $request->input('description');
        if(!$description || !strlen(trim($description))){
            $error = true;
            $message = 'Post needs a description.';
        }
        $tags = $request->input('tags');
        if(!$tags || !strlen(trim($tags))){
            $error = true;
            $message = 'At least one tag is required.';
        }
        $slug = $request->input('slug');
        if(!$slug || !strlen(trim($slug))){
            $error = true;
            $message = 'Slug is required.';
        }
        $user_id = $request->input('user_id');
        if(!$user_id || !strlen(trim($user_id))){
            $error = true;
            $message = 'Author is required.';
        }
        $status = $request->input('status');
        if(!$status || !strlen(trim($status))){
            $error = true;
            $message = 'Status is required.';
        }
        $content = $request->input('content');
        if(!$content || !strlen(trim($content))){
            $error = true;
            $message = 'Post needs content.';
        }
        $title = $request->input('title');
        if(!$title || !strlen(trim($title))){
            $error = true;
            $message = 'Post needs a title.';
        }
        //Input error
        if($error){
            return response()->json(['error'=>$error, 'message'=>$message]);
        }
        //Insert and return the post
        $post = new BLogPosts();
        if($request->input('file')){
            //copy image from temp
            if(is_file(base_path(). self::IMAGE_UPLOADS_TEMP.'/'.$request->input('file'))){
                copy(   base_path(). self::IMAGE_UPLOADS_TEMP.'/'.$request->input('file'),
                        base_path(). self::IMAGE_UPLOADS.'/'.$request->input('file'));
            }
            //remove temp files
            $temp_folder = base_path(). self::IMAGE_UPLOADS_TEMP;
            $files = glob($temp_folder.'/*');
            foreach($files as $file) {
                if(is_file($file))
                    // Delete the given file
                    unlink($file);
            }
            $post->image = $request->input('file');
        }else{
            return response()->json(['error'=>true, 'message'=>'Image is required']);
        }
        $post->title = trim($title);
        $post->content = $request->input('content');
        $post->user_id = $request->input('user_id');
        $post->slug = $request->input('slug');
        $post->tags = $request->input('tags');
        $post->description = $request->input('description');
        $post->status = $request->input('status');
        $post->comments = 0;
        $post->save();
        return response()->json(['success'=>true, 'post'=>$post]);
    }



    public function delete($path = 'educators', $id){
        $post = BlogPosts::find($id);
        if(!$post){
            return response()->json(['success'=>false, 'messaage'=>'Invalid ID']);
        }
        BlogPosts::destroy($id);
        return response()->json(['success'=>true, 'messaage'=>'Post deleted.']);
    }

}

