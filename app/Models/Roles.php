<?php

namespace App\Models;

use Faker\Generator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Validation\Rule;


class Roles extends Model {

    public $timestamps = false;
    
    use HasFactory;

    public function migration(Blueprint $table) {
        $table->id();
        $table->string('role', 25)->nullable();
        $table->string('description', 500)->nullable();
    }

    public static function definition(Generator $faker) {
        return [
            'role' => $faker->name,
        ];
    }

    public static function rules(Roles $roles = null) {
        return [
            'role' => ['required', Rule::unique('roles')->ignore($roles->id ?? null)],
        ];
    }

    public function permissions() {
        return $this->belongsToMany(Permissions::class, 'roles_permissions', 'role_id', 'permission_id');
    }

    public function users() {
        return $this->belongsToMany(User::class, 'user_roles', 'user_id', 'role_id');
    }
    
    public function hasPermission($permission){
        foreach($this->permissions as $perm){
            if($perm->id == $permission->id)
                return true;
        }
        return false;
    }

}
