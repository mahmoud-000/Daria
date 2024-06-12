<?php

namespace Modules\Department\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Modules\Department\Models\Department;
use Illuminate\Support\Str;

class DepartmentImportCsv extends Controller
{
    public function __invoke(Request $request)
    {
        if (!auth()->user()->is_owner)  abort_if(Gate::denies('import-csv-department'), Response::HTTP_FORBIDDEN, __('permission::messages.gate_denies'));
        try {
            $departments = $request->all();
            $removed = array_shift($departments);
            $departmentsArray = [];
            foreach ($departments as $key => $value) {
                $departmentsArray[] = [
                    'name' => $value['name'],
                    'is_active' => isset($value['is_active']) && in_array(Str::lower($value['is_active']), Controller::ACTIVE_ARRAY) ? true : false,
                ];
            }
            $errors = [];
            foreach ($departmentsArray as $department) {
                $validator = Validator::make($department, [
                    'name'          => ['required', 'string', 'min:3', 'max:100', Rule::unique('departments', 'name')->withoutTrashed()],
                    'is_active'     => ['required', 'boolean'],
                    'remarks'       => ['string', 'nullable'],
                ]);

                if ($validator->fails()) {
                    $errors[$department['name']] = $validator->errors();
                }
            }
            if ($errors) {
                return $this->error(['errors' => $errors], Response::HTTP_UNPROCESSABLE_ENTITY);
            }
            Department::upsert($departmentsArray, ['email']);
            return $this->success(__('status.imported_csv_successfully'));
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->error(__('status.import_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            return $this->error(__('status.import_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
