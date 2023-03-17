<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_photo_path',
        'role_id',
        'department_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public function canAccess($permission)
    {
        if (!@$this->id) return false;
        return UserPermission::where("user_id", $this->id)->whereHas('permission', function ($query) use ($permission) {
            $query->where('name', $permission);
        })->exists();
    }

    public function setPermissions($permissions)
    {
        if(!@$this->id) return;

        $user_id = $this->id;
        UserPermission::where('user_id', $user_id)->delete();
        $permissions = Permission::whereIn("name", $permissions)->pluck("id")->toArray();
        $permissions = array_map(function($permission) use ($user_id) {
            return [
                "permission_id" => $permission,
                "user_id" => $user_id,
                "created_at" => date("Y-m-d H:i:s"),
                "updated_at" => date("Y-m-d H:i:s")
            ];
        }, $permissions);
        UserPermission::insert($permissions);
    }

    public function permissions()
    {
        return $this->hasManyThrough(Permission::class, UserPermission::class, "user_id", "id", "id", "permission_id");
    }

    public function department()
    {
        return $this->belongsTo(Department::class)->withTrashed();
    }

    public function getPermissionNamesAttribute()
    {
        return $this->permissions()->pluck("name")->toArray();
    }

    public function getFormattedPermissionsAttribute($query)
    {
        $permissions = $this->permissions()->pluck("name")->toArray();
        $formatted_permissions = [];

        foreach($permissions as $permission) {
            $p = explode("-", $permission);
            if(!isset($formatted_permissions[$p[0]])) {
                $formatted_permissions[$p[0]] = [];
            }
            array_push($formatted_permissions[$p[0]], $p[1]);
        }
        return $formatted_permissions;
    }
    
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'email', 'department.name'])
            ->logOnlyDirty();
    }
}
