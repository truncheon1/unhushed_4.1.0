<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Models\CurriculumDocument;

class MoveCurriculaFiles extends Command
{
    protected $signature = 'curricula:move-public-to-private {--dry-run : List files without moving}';
    protected $description = 'Move existing curriculum files from public/uploads/subscriptions to private curricula disk.';

    public function handle(): int
    {
        $dry = $this->option('dry-run');
        $publicBase = public_path('uploads/subscriptions');
        $targetDisk = Storage::disk('curricula');
        File::ensureDirectoryExists($targetDisk->path(''));

        $docs = CurriculumDocument::whereNotNull('document')->where('document', '<>', '')->get();
        $moved = 0; $skipped = 0; $missing = 0; $exists = 0;

        foreach($docs as $doc){
            $name = $doc->document;
            $publicPath = $publicBase . DIRECTORY_SEPARATOR . $name;
            $privateExists = $targetDisk->exists($name);
            if($privateExists){
                $exists++; continue; // already migrated
            }
            if(!File::exists($publicPath)){
                $missing++; continue; // nothing to move
            }
            if($dry){
                $this->line("DRY: would move $name");
                $skipped++; continue;
            }
            try {
                $targetDisk->put($name, File::get($publicPath));
                $moved++;
            } catch(\Throwable $e){
                $this->error("Failed to move $name: " . $e->getMessage());
            }
        }

        $this->info("Migration summary: moved=$moved already_exists=$exists missing_in_public=$missing dry_listed=$skipped");
        if($dry){
            $this->warn('Dry run only. Re-run without --dry-run to execute moves.');
        }
        return Command::SUCCESS;
    }
}
