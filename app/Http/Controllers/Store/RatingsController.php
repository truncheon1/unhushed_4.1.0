<?php

namespace App\Http\Controllers\Store;
use App\Http\Controllers\Controller;
use App\Models\ProductRatings;

class RatingsController extends Controller
{
    public $ratings;
    public $comment;
    public $currentId;
    public $product;
    public $hideForm;

    protected $rules = [
        'ratings' => ['required', 'in:1,2,3,4,5'],
        'comment' => 'required',
    ];

    public function mount(){
        if(auth()->user()){
            $ratings = ProductRatings::where('user_id', auth()->user()->id)->where('product_id', $this->product->id)->first();
            if (!empty($ratings)) {
                $this->ratings  = $ratings->ratings;
                $this->comment = $ratings->comment;
                $this->currentId = $ratings->id;
            }
        }
        return view('store.ratings.reviews');
    }

    public function delete($id){
        $ratings = ProductRatings::where('id', $id)->first();
        if ($ratings && ($ratings->user_id == auth()->user()->id)) {
            $ratings->delete();
        }
        if ($this->currentId) {
            $this->currentId = '';
            $this->ratings  = '';
            $this->comment = '';
        }
    }

    public function rate(){
        $ratings = ProductRatings::where('user_id', auth()->user()->id)->where('product_id', $this->product->id)->first();
        $this->validate();
        if (!empty($Ratings)) {
            $ratings->user_id = auth()->user()->id;
            $ratings->product_id = $this->product->id;
            $ratings->ratings = $this->ratings;
            $ratings->comment = $this->comment;
            $ratings->status = 1;
            try {
                $ratings->update();
            } catch (\Throwable $th) {
                throw $th;
            }
            session()->flash('message', 'Success!');
        } else {
            $ratings = new ProductRatings;
            $ratings->user_id = auth()->user()->id;
            $ratings->product_id = $this->product->id;
            $ratings->Ratings = $this->Ratings;
            $ratings->comment = $this->comment;
            $ratings->status = 1;
            try {
                $ratings->save();
            } catch (\Throwable $th) {
                throw $th;
            }
            $this->hideForm = true;
        }
    }

}
