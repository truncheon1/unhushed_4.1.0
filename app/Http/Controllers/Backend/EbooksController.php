<?php

namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Controller;
use App\Models\ProductFiles;
use App\Models\Products;
use Illuminate\Support\Facades\DB;

class EbooksController extends Controller
{
    ///Book delivery///
    //Main index
    public function backend_catalog($path = 'professional'){
        $authId = \Illuminate\Support\Facades\Auth::id();
        
        // Collect all distinct purchased book product IDs (category = 3, completed purchases)
        $purchasedProductIds = DB::table('purchase_items')
            ->join('purchase_carts', 'purchase_items.cart_id', '=', 'purchase_carts.id')
            ->join('products', 'purchase_items.product_id', '=', 'products.id')
            ->where('purchase_carts.user_id', $authId)
            ->where('purchase_carts.completed', 2)
            ->where('products.category', 3)
            ->distinct()
            ->pluck('purchase_items.product_id')
            ->toArray();

        // Load all purchased book products with variants eager-loaded
        $bookProducts = collect();
        if(!empty($purchasedProductIds)){
            $bookProducts = Products::with(['images', 'vars'])
                ->whereIn('id', $purchasedProductIds)
                ->get();
        }

        // Ebooks: category=3 products with any variant having ship_type=0 (digital)
        $ebooks = $bookProducts->filter(function($p){
            return $p->vars->contains(function($v){
                return (int)$v->ship_type === 0;
            });
        })->values();

        // Books: category=3 products with any variant having ship_type=2 (physical media mail)
        $books = $bookProducts->filter(function($p){
            return $p->vars->contains(function($v){
                return (int)$v->ship_type === 2;
            });
        })->values();        return view('backend.content-books.index')
        ->with('books', $books)
        ->with('ebooks', $ebooks)
        ->with('section', 'books')
        ->with('path', get_path($path));
    }

    public function books($path = 'professional', $digital_slug){
        $book = Products::where('slug', $digital_slug)->first();
        if(!$book)
            abort(404);
        $blade = 'backend.content-books.'.$book->template;
        $files = ProductFiles::where('product_id', $book->reference_id)->get();
        return view($blade)
        ->with('book', $book)
        ->with('files', $files)
        ->with('path', get_path($path));
    }

    public function ebooks($path = 'professional', $digital_slug){
        $ebook = Products::where('slug', $digital_slug)->first();
        if(!$ebook)
            abort(404);
        $blade = 'backend.content-books.ebooks';
        $files = ProductFiles::where('product_id', $ebook->reference_id)->get();
        return view($blade)
        ->with('ebook', $ebook)
        ->with('files', $files)
        ->with('path', get_path($path));
    }
}
