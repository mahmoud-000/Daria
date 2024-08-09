<?php

namespace Modules\Department\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Arr;
use Modules\Department\Models\Department;
use Modules\Department\Http\Requests\UpdateDepartmentRequest;

class DepartmentUpdate extends Controller
{
    public function __invoke(UpdateDepartmentRequest $request, Department $department)
    {
        try {
            $department->update(Arr::except($request->validated(), ['department', 'manager']));

            return $this->success(__('status.updated', ['name' => $department['name'], 'module' => __('modules.department')]));
        } catch (\Illuminate\Database\QueryException $e) {
            $this->error(__('status.update_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            $this->error(trans('status.update_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
