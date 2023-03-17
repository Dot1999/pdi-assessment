<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function scopeFormattedPermissions($query)
    {
        $permissions = $query->get();
        $formatted_permissions = [];

        foreach($permissions as $permission) {
            $p = explode("-", $permission->name);
            if(!isset($formatted_permissions[$p[0]])) {
                $formatted_permissions[$p[0]] = [];
            }
            array_push($formatted_permissions[$p[0]], $p[1]);
        }
        return $formatted_permissions;
    }
}
