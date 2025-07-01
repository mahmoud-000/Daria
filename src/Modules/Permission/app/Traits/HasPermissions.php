<?php

namespace Modules\Permission\Traits;

use Modules\Permission\Models\Permission;
use Modules\Role\Models\Role;

trait HasPermissions
{
  public function getAllPermissions()
  {
    return [...$this->permissions()->get()->pluck('slug')->toArray(), ...$this->roles()->get()->pluck('permissions')->flatten()->pluck('slug')->toArray()];
  }

  public function roles()
  {
    return $this->morphToMany(Role::class, 'model', 'model_has_roles')->withTrashed();
  }

  public function hasRole(...$roles)
  {
    return $this->roles()->whereIn('slug', $roles)->count();
  }

  public function permissions()
  {
    return $this->morphToMany(Permission::class, 'model', 'model_has_permissions');
  }

  public function hasPermissionsTo(...$permissions)
  {
    return $this->permissions()->whereIn('slug', $permissions)->count() ||
      $this->roles()->whereHas('permissions', function ($q) use ($permissions) {
        $q->whereIn('slug', $permissions);
      })->count();
  }

  public function findPermissionsByName($permissions)
  {
    return Permission::whereIn('name', $permissions)->get()->pluck('id')->toArray();
  }

  public function givePermissionsTo($permissions)
  {
    $this->permissions()->attach($this->findPermissionsByName($permissions));
  }

  public function setPermissions($permissions)
  {
    $this->permissions()->sync($this->findPermissionsByName($permissions));
  }

  public function detachPermissions($permissions)
  {
    $this->permissions()->detach($this->findPermissionsByName($permissions));
  }
}
