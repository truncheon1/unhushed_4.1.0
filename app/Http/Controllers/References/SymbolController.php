<?php

namespace App\Http\Controllers\References;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dictionary;

class SymbolController extends Controller
{
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

    public function view($path = 'educators', $id){
        $entry = Dictionary::find($id);
        if(!$entry){
            return response()->json(['success'=>false, 'messaage'=>'Invalid ID']);
        }
        return response()->json(['success'=>true, 'entry'=>$entry]);
    }

    public function update_symbol(Request $request, $path = 'educators'){
        $entry = Dictionary::find($request->input('id'));
        if($request->input('file') && $request->input('file') !== $entry->symbol){
            if(is_file(base_path(). self::IMAGE_UPLOADS_TEMP.'/'.$request->input('file'))){
                copy(   base_path(). self::IMAGE_UPLOADS_TEMP.'/'.$request->input('file'),
                        base_path(). self::IMAGE_UPLOADS.'/'.$request->input('file'));
            }
            //remove temp files
            $temp_folder = base_path(). self::IMAGE_UPLOADS_TEMP;
            $files = glob($temp_folder.'/*');
            foreach($files as $file) {
                if(is_file($file))
                    // Delete the old file
                    unlink($file);
            }
            $entry->symbol = $request->input('file');
        }else{
            return response()->json(['error'=>true, 'message'=>'Image Required']);
        }
        $entry->save();
        return response()->json(['success'=>true, 'entry'=>$entry]);
    }

    public function delete_symbol($path = 'educators', $id){
        $entry = Dictionary::find($id);
        if(!$entry){
            return response()->json(['success'=>false, 'messaage'=>'Invalid ID']);
        }
        Dictionary::destroy($entry->symbol);
        return response()->json(['success'=>true, 'messaage'=>'Symbol has been deleted.']);
    }
}
