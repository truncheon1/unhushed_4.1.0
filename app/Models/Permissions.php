<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Roles;

class Permissions extends Model {

    use HasFactory;
    public $timestamps = false;

    public function roles(){
        return $this->belongsToMany(Roles::class, 'roles_permissions', 'permission_id', 'role_id');
    }

    public function users(){
        return $this->belongsToMany(User::class, 'users_permissions');
    }
}
