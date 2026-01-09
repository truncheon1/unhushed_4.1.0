<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurriculumDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'unit_id',
        'session_id',
        'document',
        'number',
        'keywords',
        'name',
        'filenfo',
        'type',
        'url',
        'time',
    ];
}
