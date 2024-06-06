<?php

namespace Modules\Department\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
use Modules\Department\Models\Department;
use Modules\Department\Transformers\DepartmentResource;

class DepartmentShow extends Controller
{
    public function __invoke(Department $department)
    {
        if (!auth()->user()->is_owner)  abort_if(Gate::denies('show-department'), Response::HTTP_FORBIDDEN, __('permission::messages.gate_denies'));
        return DepartmentResource::make($department->load(['parent']));
    }
}
