<?php
namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Controller;
use App\Models\ProductAssignments;
use App\Models\Products;
use App\Models\ProductVar;

class TrainingController extends Controller
{

    //Backend Trainings delivery
    public function back_catalog($path = 'educators'){
        // Get user's active training assignments with product and variant data
        $assignments = ProductAssignments::where('user_id', auth()->user()->id)
            ->where('active', 1)
            ->where('category', 7)
            ->with(['product', 'variant'])
            ->get();

        // Extract unique products with their variant info, sorted by product sort order
        $trainings = $assignments
            ->pluck('product')
            ->filter()
            ->unique('id')
            ->sortBy('sort')
            ->values();

        // Attach variant information to each product
        foreach ($trainings as $training) {
            $training->assigned_variant = $assignments
                ->where('product_id', $training->id)
                ->first()
                ->variant ?? null;
        }

        return view('backend.content-trainings.index')
            ->with('trainings', $trainings)
            ->with('section', 'training')
            ->with('path', get_path($path));
    }
}
