<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\ProductImages;

class NormalizeProductImageSorts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:normalize-image-sorts {--dry-run : Show planned changes without updating}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reindex product_images.sort per product starting at 1 and ensure only one primary (sort=1)';

    public function handle(): int
    {
        $dry = $this->option('dry-run');

        $this->info($dry ? 'Running in DRY RUN mode. No database changes will be saved.' : 'Normalizing product image sort ordering...');

        // Get distinct product_ids that have images
        $productIds = ProductImages::query()
            ->select('product_id')
            ->distinct()
            ->pluck('product_id');

        $totalProducts = $productIds->count();
        $updatedProducts = 0;
        $totalImages = 0;

        foreach ($productIds as $pid) {
            $images = ProductImages::where('product_id', $pid)
                ->orderBy('sort', 'ASC')
                ->orderBy('id', 'ASC')
                ->get();

            if ($images->isEmpty()) {
                continue;
            }

            $needsUpdate = false;
            $counter = 1;
            $planned = [];
            foreach ($images as $img) {
                if ((int)$img->sort !== $counter) {
                    $needsUpdate = true;
                }
                $planned[] = [ 'id' => $img->id, 'old' => $img->sort, 'new' => $counter, 'file' => $img->image ];
                $counter++;
            }

            if ($needsUpdate) {
                if ($dry) {
                    $this->line("Product #{$pid} would be updated:");
                    foreach ($planned as $p) {
                        $this->line("  image_id={$p['id']} {$p['file']} sort {$p['old']} -> {$p['new']}");
                    }
                } else {
                    DB::transaction(function () use ($planned) {
                        foreach ($planned as $p) {
                            ProductImages::where('id', $p['id'])->update(['sort' => $p['new']]);
                        }
                    });
                    $updatedProducts++;
                }
            }
            $totalImages += $images->count();
        }

        if ($dry) {
            $this->info("DRY RUN complete. {$totalProducts} products scanned, {$totalImages} images checked.");
        } else {
            $this->info("Normalization complete. {$totalProducts} products scanned, {$updatedProducts} products updated, {$totalImages} images processed.");
        }

        return Command::SUCCESS;
    }
}
