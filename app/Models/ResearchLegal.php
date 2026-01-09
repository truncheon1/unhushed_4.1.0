<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResearchLegal extends Model
{
    use HasFactory;
    protected $table = 'research_legal';
    protected $fillable = [
        'state', 'column', 'code', 'info'
    ];
}
