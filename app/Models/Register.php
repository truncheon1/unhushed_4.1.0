<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Register extends Model
{
    use HasFactory;
    public $table = 'registrations';
    public $fillable = ['name','email','street','city', 'state', 'zip'];
}
