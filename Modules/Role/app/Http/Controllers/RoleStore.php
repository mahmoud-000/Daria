<?php

namespace Modules\Role\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Modules\Permission\Models\Permission;
use Modules\Role\Models\Role;
use Modules\Role\Http\Requests\StoreRoleRequest;

class RoleStore extends Controller
{
    public function __invoke(StoreRoleRequest $request)
    {
        try {
            $request = $request->validated();
            $role = DB::transaction(function () use ($request) {
                $slug = str()->slug($request['name']);
                $role = Role::create(Arr::except($request, ['permissions']) + ['slug' => $slug]);
        
                if (isset($request['permissions']) && count($request['permissions'])) {
                    $ids = Permission::whereIn('name', $request['permissions'])->get()->pluck('id');
                    $role->permissions()->attach($ids);
                }

                return $role;
            });
            return $this->success(__('status.created', ['name' => $role['name'], 'module' => __('modules.role')]));
        } catch (\Illuminate\Database\QueryException $e) {
            $this->error(__('status.create_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            $this->error(trans('status.create_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
