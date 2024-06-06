<?php

namespace Modules\Department\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
use Modules\Department\Models\Department;

class DepartmentDestroy extends Controller
{
    public function __invoke(Department $department)
    {
        if (!auth()->user()->is_owner)  abort_if(Gate::denies('delete-department'), Response::HTTP_FORBIDDEN, __('permission::messages.gate_denies'));
        try {
            $department->delete();
            return $this->success(__('status.deleted', ['name' => $department->name, 'module' => __('modules.department')]));
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->error(__('status.delete_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            return $this->error(__('status.delete_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
