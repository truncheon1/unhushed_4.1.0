<?php

namespace App\Models;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Traits\HasPermissionsTrait;
use App\Models\ProductAssignments;
use Laravel\Cashier\Billable;


class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasPermissionsTrait, Billable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function roles_string($sep = ", "){
        $roles = $this->roles;
        $collect = [];
        foreach($roles as $role){
            $collect[] = $role->role;
        }
        return (implode($sep, $collect));
    }

    public function permissions_string($sep = ", "){
        $roles = $this->roles;
        $collect = [];
        foreach($roles as $role){
            foreach($role->permissions as $p){
                $collect[] = $role->role.":".$p->slug;
            }
        }
        $permissions = $this->permissions;
        foreach($permissions as $p){
            $collect[] = "user_permission:".$p->slug;
        }
        return (implode($sep, $collect));
    }

    public function packages_string($sep = "/"){
        // get assigned package ids for this user (preserve order)
        // use DISTINCT to avoid duplicate package_id rows for the same user
        $assigned = ProductAssignments::where('user_id', $this->id)
            ->where('product_id', '>', '1')
            ->where('active', 1)
            ->orderBy('product_id', 'asc')
            ->distinct()
            ->pluck('product_id')
            ->toArray();

        if (empty($assigned)) {
            return '';
        }

        // fetch all package names in one query to avoid N+1
        $packages = \App\Models\Products::where('category', 1)->whereIn('id', $assigned)
            ->pluck('name', 'id')
            ->toArray();

        $collect = [];
        foreach ($assigned as $pkgId) {
            $name = $packages[$pkgId] ?? null;
            if (empty($name)) {
                continue;
            }
            // split into words and build an acronym from the first character of each word
            $words = preg_split('/\s+/', trim($name));
            $acronym = '';
            foreach ($words as $w) {
                if ($w === '') continue;
                $first = mb_strtoupper(mb_substr($w, 0, 1));
                // skip words that start with 'C' per request
                if ($first === 'C') continue;
                $acronym .= $first;
            }
            if ($acronym !== '') {
                $collect[] = $acronym;
            }
        }

        // remove duplicates in case different packages produce the same acronym
        $collect = array_values(array_unique($collect));

        return implode($sep, $collect);
        
    }

    public function packages(){
        return $this->hasMany('App\Models\ProductAssignments', 'user_id');
    }

    /**
     * Return an ordered list of acronyms with their full package names for tooltip display.
     * Example return: [ ['acronym' => 'BC', 'names' => ['Basic Course']], ... ]
     */
    public function packages_with_names(){
        // get distinct assigned package ids for this user (preserve order)
        $assigned = ProductAssignments::where('user_id', $this->id)
            ->where('product_id', '>', '1')
            ->where('active', 1)
            ->orderBy('product_id', 'asc')
            ->distinct()
            ->pluck('product_id')
            ->toArray();

        if (empty($assigned)) return [];

        $packages = \App\Models\Products::where('category', 1)->whereIn('id', $assigned)
            ->pluck('name', 'id')
            ->toArray();

        $map = [];
        $order = [];
        foreach ($assigned as $pkgId) {
            $name = $packages[$pkgId] ?? null;
            if (empty($name)) continue;
            $words = preg_split('/\s+/', trim($name));
            $acronym = '';
            foreach ($words as $w) {
                if ($w === '') continue;
                $first = mb_strtoupper(mb_substr($w, 0, 1));
                if ($first === 'C') continue; // follow rule to skip C
                $acronym .= $first;
            }
            if ($acronym === '') continue;
            if (!isset($map[$acronym])){
                $map[$acronym] = [];
                $order[] = $acronym;
            }
            if (!in_array($name, $map[$acronym])){
                $map[$acronym][] = $name;
            }
        }

        $result = [];
        foreach ($order as $ac) {
            $result[] = ['acronym' => $ac, 'names' => $map[$ac]];
        }
        return $result;
    }

    public function is_assigned($id, $active = 1){ //curriculum (was type 0 now category 1)
        return ProductAssignments::where('product_id', $id)->where('category', 1)->where('user_id', $this->id)->where('active', $active)->exists();
    }

    public function licenses($sep = ", "){
        // get distinct assigned license package ids for the user
        $assigned = ProductAssignments::where('user_id', $this->id)
            ->where('category', 1)
            ->where('active', '1')
            ->distinct()
            ->pluck('product_id')
            ->toArray();

        if (empty($assigned)) {
            return '';
        }

        $packages = \App\Models\Products::where('category', 1)->whereIn('id', $assigned)
            ->pluck('name', 'id')
            ->toArray();

        $collect = [];
        foreach ($assigned as $pkgId) {
            $name = $packages[$pkgId] ?? null;
            if (empty($name)) continue;
            $words = preg_split('/\s+/', trim($name));
            $acronym = '';
            foreach ($words as $w) {
                if ($w === '') continue;
                $acronym .= mb_strtoupper(mb_substr($w, 0, 1));
            }
            if ($acronym !== '') $collect[] = $acronym;
        }

        // remove duplicates just in case
        $collect = array_values(array_unique($collect));
        return implode($sep, $collect);
    }

    public function is_assigned_training($id){ //training (was type 4 now category 7)
        return ProductAssignments::where('product_id', $id)->where('category', 7)->where('user_id', $this->id)->exists();
    }

    public function is_assigned_activity($id){ //digital activity (unchanged mapping 1)
        return ProductAssignments::where('product_id', $id)->where('category', 1)->where('user_id', $this->id)->exists();
    }

    public function is_assigned_type($id, $category){
        return ProductAssignments::where('product_id', $id)->where('category', $category)->where('user_id', $this->id)->exists();
    }

    public function post(){
        return $this->hasMany('App\Models\Posts', 'user_id');
    }
    
    /**
     * Check if user is tax exempt
     */
    public function isTaxExempt(): bool
    {
        // Check individual exemption override first
        if ($this->tax_exempt_override) {
            return true;
        }
        
        // Check organization exemption
        if ($this->org_id) {
            $org = Organizations::find($this->org_id);
            if ($org && $org->tax_exempt && $org->tax_exempt_verified_at) {
                // Check if exemption hasn't expired
                if (!$org->tax_exempt_expiry || $org->tax_exempt_expiry >= now()) {
                    return true;
                }
            }
        }
        
        return false;
    }
    
    /**
     * Get Stripe tax ID type for exemption
     */
    public function getTaxIdType(): ?string
    {
        if ($this->org_id) {
            $org = Organizations::find($this->org_id);
            if ($org && $org->tax_exempt && $org->tax_exempt_type) {
                // Map organization tax exemption types to Stripe tax ID types
                $typeMap = [
                    'nonprofit' => 'us_ein',
                    'government' => 'us_ein',
                    'educational' => 'us_ein',
                    'religious' => 'us_ein',
                ];
                
                return $typeMap[$org->tax_exempt_type] ?? 'us_ein';
            }
        }
        
        return null;
    }
    
    /**
     * Get tax exemption ID (EIN, etc.)
     */
    public function getTaxExemptId(): ?string
    {
        if ($this->org_id) {
            $org = Organizations::find($this->org_id);
            return $org->tax_exempt_id ?? null;
        }
        
        return null;
    }
}
