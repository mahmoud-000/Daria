<?php

namespace Modules\Role\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
use Modules\Role\Models\Role;

class RoleDestroy extends Controller
{
    public function __invoke(Role $role)
    {
        if (!auth()->user()->is_owner)  abort_if(Gate::denies('delete-role'), Response::HTTP_FORBIDDEN, __('permission::messages.gate_denies'));
        try {
            $role->delete();
            return $this->success(__('status.deleted', ['name' => $role->name, 'module' => __('modules.role')]));
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->error(__('status.delete_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            return $this->error(__('status.delete_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
