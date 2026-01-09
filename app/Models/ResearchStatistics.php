<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResearchStatistics extends Model
{
    use HasFactory;
    protected $table = 'research_stats';
    protected $fillable = [
        'title', 'author', 'journal', 'year', 'month', 'keywords', 'url', 'abstract'
    ];
}
