<?php

namespace Modules\Department\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Arr;
use Modules\Department\Models\Department;
use Modules\Department\Http\Requests\StoreDepartmentRequest;

class DepartmentStore extends Controller
{
    public function __invoke(StoreDepartmentRequest $request)
    {
        try {
            $department = Department::create(Arr::except($request->validated(), ['department', 'manager']));

            return $this->success(__('status.created', ['name' => $department['name'], 'module' => __('modules.department')]));
        } catch (\Illuminate\Database\QueryException $e) {
            $this->error(__('status.create_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            $this->error(trans('status.create_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
