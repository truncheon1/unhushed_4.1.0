<?php

namespace App\Traits;

use App\Models\Permissions;
use App\Models\Roles;
use Illuminate\Support\Facades\DB;


trait HasPermissionsTrait {

    public function givePermissionsTo(...$permissions) {
        if ($permissions === null) {
            return this;
        }
        $this->permissions()->saveMany($permissions);
        return $this;
    }

    public function removePermissionsTo(... $permissions) {
        $permissions = $this->getAllPermissions($permissions);
        $this->permissions()->detach($permissions);
        return $this;
    }

    public function reloadPermissions(... $permissions) {
        $this->permissions()->detach();
        return $this->givePermissionsTo($permissions);
    }



    public function hasPermissionTo($permission) {
        return $this->hasPermissionThroughRole($permission) || $this->hasPermission($permission);
    }

    public function hasPermissionThroughRole($permission) {
        foreach ($permission->roles as $role) {
            if($this->roles->contains($role)) {
                return true;
            }
        }

        return false;
    }

    public function hasRole(... $roles) {
        foreach ($roles as $role) {
            if ($this->roles->contains('role', $role)) {
                return true;
            }
        }

        return false;
    }

    public function addRole($role){
        if(!is_object($role)){
            $role = \App\Models\Roles::where('role', $role)->first();
        }
        if(!$this->hasRole($role->role)){
            $this->roles()->save($role);
        }
    }

    public function removeRole($role){
        if(!is_object($role)){
            $role = \App\Models\Roles::where('role', $role)->first();
        }
        if($this->hasRole($role->role)){
            DB::table('user_roles')->where('role_id', $role->id)->where('user_id', $this->id)->delete();

        }
    }

    public function roles() {
        return $this->belongsToMany(\App\Models\Roles::class, 'user_roles', 'user_id', 'role_id');
    }

    public function permissions() {
        return $this->belongsToMany(\App\Models\Permissions::class, 'user_permissions', 'user_id', 'permission_id');
    }

    public function hasPermission($permission) {
        $slug = is_object($permission) ? $permission->slug : $permission;
        return (bool) $this->permissions->where('slug', $slug)->count();
    }

    protected function getAllPermissions(array $permissions) {
        return Permissions::whereIn('slug', $permissions)->get();
    }

}
