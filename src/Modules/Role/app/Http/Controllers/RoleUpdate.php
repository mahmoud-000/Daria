<?php

namespace Modules\Role\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Notifications\CreateRoleNotification;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Modules\Permission\Models\Permission;
use Modules\Role\Models\Role;
use Modules\Role\Http\Requests\UpdateRoleRequest;

class RoleUpdate extends Controller
{
    public function __invoke(UpdateRoleRequest $request, Role $role)
    {
        try {
            $request = $request->validated();
            
            $role = DB::transaction(function () use ($role, $request) {
                $slug = str()->slug($request['name']);
                $role->update(Arr::except($request, ['permissions']) + ['slug' => $slug]);
                if (isset($request['permissions']) && count($request['permissions'])) {
                    $ids = Permission::whereIn('name', $request['permissions'])->get()->pluck('id');
                    $role->permissions()->sync($ids);
                } else {
                    $role->permissions()->detach();
                }

                return $role;
            });

            return $this->success(__('status.updated', ['name' => $role['name'], 'module' => __('modules.role')]));
        } catch (\Illuminate\Database\QueryException $e) {
            $this->error(__('status.update_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            $this->error(trans('status.update_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
