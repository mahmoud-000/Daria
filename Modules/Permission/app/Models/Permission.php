<?php

namespace Modules\Permission\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\User\Models\User;

class Permission extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'slug', 'guard_name'];
   
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_has_permissions');
    }

    public function users()
    {
        return $this->morphedByMany(User::class, 'mdoel');
    }
}
