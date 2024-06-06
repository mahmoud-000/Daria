<?php

namespace Modules\Permission\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Permission\Models\Permission;
use Modules\Permission\Transformers\PermissionResource;

class PermissionOptions extends Controller
{
    public function __invoke()
    {
        $permissions = Permission::select(['name', 'guard_name'])->get()->groupBy('guard_name')->toArray();
        $mapPermissions = [];
        foreach ($permissions as $key => $value) {
            $mapPermissions[] = [
                'name' => $key,
                'children' => $permissions[$key],
            ];
        }
        return response()->json($mapPermissions);
    }
}
