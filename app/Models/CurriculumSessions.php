<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurriculumSessions extends Model
{
    use HasFactory;

    public function documents(){
        return $this->hasMany(\App\Models\CurriculumDocument::class, 'session_id')->orderBy('number', 'ASC')->get();
    }
}
