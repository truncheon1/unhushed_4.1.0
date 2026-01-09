<?php

namespace App\Http\Controllers;
use App\Models\CurriculumDocument;
use App\Models\CurriculumSessions;
use App\Models\CurriculumUnits;
use App\Models\ProductAssignments;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class DownloadController extends Controller
{

    public function fix_names(){
        $docs = CurriculumDocument::where('filenfo', '<>', '')->get();
        foreach($docs as $doc){
            dump($doc->document);
            dump($doc->filenfo);
            $parts = explode(".", $doc->filenfo);
            $str = '';
            $ext = array_pop($parts);
            $new_slug = Str::slug(implode(" ", $parts), "-").".".$ext;
            try{
                Storage::disk('dld')->copy('uploads/subscriptions/'.$doc->document, 'uploads/subscriptions/'.$new_slug);
                $doc->document = $new_slug;
                $doc->save();
            }
            catch(\League\Flysystem\FileExistsException $e){}
            catch(\League\Flysystem\FileNotFoundException $e){}
        }
    }

    public function index($file){
        // Find document record by its filenfo (display name hash) OR stored filename
        $doc = CurriculumDocument::where('filenfo', $file)
            ->orWhere('document', $file)
            ->first();
        if(!$doc){
            abort(404); // No metadata for this file
        }
        if(!Auth::check()){
            abort(403); // must be logged in
        }

        // Resolve session -> unit to determine product (curriculum) ownership
        $session = CurriculumSessions::find($doc->session_id);
        if(!$session){
            abort(404); // orphaned document
        }
        $unit = CurriculumUnits::find($session->unit_id);
        if(!$unit){
            abort(404); // orphaned session
        }
        $currentUserId = Auth::id() ?? 0;
        if(!ProductAssignments::userHasCurriculum($currentUserId, (int)$unit->product_id)){
            abort(403); // not authorized for this curriculum
        }

        $filename = $doc->document; // stored physical filename (unchanged)

        // Serve ONLY from private curricula disk; public fallback removed
        if (!Storage::disk('curricula')->exists($filename)) {
            abort(404); // file not migrated or missing
        }
        $path = Storage::disk('curricula')->path($filename);

        $mime = @mime_content_type($path) ?: 'application/octet-stream';
        $downloadName = $file; // preserve original request name for download
        // Always stream (no public redirect) to avoid exposing storage path
        if($this->is_for_browser($mime)){
            return response()->file($path, [
                'Cache-Control' => 'no-store, no-cache, must-revalidate, private',
                'Pragma' => 'no-cache',
                'X-Content-Type-Options' => 'nosniff'
            ]);
        }
        return response()->download($path, $downloadName, [
            'Cache-Control' => 'no-store, no-cache, must-revalidate, private',
            'Pragma' => 'no-cache',
            'X-Content-Type-Options' => 'nosniff'
        ]);
    }

    private function is_for_browser($mimeType){
        $types = ['text/plain', 'application/pdf'];
        if(in_array($mimeType, $types))
            return true;
        return false;
    }
}
