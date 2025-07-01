<?php

namespace Modules\Permission\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Modules\Permission\Models\Permission;

class DefineGates extends ServiceProvider
{
    public function boot()
    {
        try {
            $permissions = Permission::all();
            foreach ($permissions as $permission) {
                Gate::define($permission->slug, function ($user) use ($permission) {
                    return $user->hasPermissionsTo($permission->slug);
                });
            }
        } catch (\Exception $e) {
        }
    }
}
