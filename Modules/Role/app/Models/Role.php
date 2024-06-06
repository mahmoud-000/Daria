<?php

namespace Modules\Role\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Permission\Models\Permission;
use Modules\Permission\Traits\HasPermissions;

class Role extends Model
{
    use SoftDeletes, HasPermissions, Searchable;

    protected $fillable = ['name', 'slug', 'is_active', 'remarks', 'guard_name', 'is_active'];

    protected $casts = [
        'is_active'         => \App\Enums\ActiveEnum::class,
    ];

    public static function searchable()
    {
        return ['name'];
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_has_permissions');
    }

    public function hasPermissionsTo(...$permissions)
    {
        return $this->permissions()->whereIn('name', $permissions)->count();
    }
}
