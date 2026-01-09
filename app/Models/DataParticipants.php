<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataParticipants extends Model
{
    use HasFactory;
    protected $table = 'data_participants';

    public function family(){
        return DataFamilies::find($this->id);
    }
}
