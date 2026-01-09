<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurriculumUnits extends Model
{
    use HasFactory;

    public function sessions(){
        return $this->hasMany(\App\Models\CurriculumSessions::class, 'unit_id')->orderBy('number', 'ASC')->get();;
    }
}
