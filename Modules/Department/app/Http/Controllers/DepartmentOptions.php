<?php

namespace Modules\Department\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Department\Transformers\DepartmentResource;
use Modules\Department\Models\Department;

class DepartmentOptions extends Controller
{
    public function __invoke(Request $req)
    {
        return DepartmentResource::collection(
            Department::query()
                ->where('is_active', true)
                ->when(
                    !empty($req->form_id),
                    fn ($query) => $query
                        ->where('id', '!=', $req->form_id)
                        ->whereNull('department_id')
                )
                ->when(!empty($req->search), fn ($query) => $query->where('name', 'LIKE', '%' . $req->search . '%'))
                ->paginate(10)
        );
    }
}
