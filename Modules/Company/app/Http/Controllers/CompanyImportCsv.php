<?php

namespace Modules\Company\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Rules\WithOutSpaces;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Modules\Company\Models\Company;
use Illuminate\Support\Str;
class CompanyImportCsv extends Controller
{
    public function __invoke(Request $request)
    {
        if (!auth()->user()->is_owner)  abort_if(Gate::denies('import-csv-company'), Response::HTTP_FORBIDDEN, __('permission::messages.gate_denies'));
        try {
            $companies = $request->all();
            $removed = array_shift($companies);
            $companiesArray = [];
            foreach ($companies as $key => $value) {
                $companiesArray[] = [
                    'name' => $value['name'],
                    'color' => isset($value['color']) ? $value['color'] : Controller::MAIN_COLOR,
                    'is_active' => isset($value['is_active']) && in_array(Str::lower($value['is_active']), Controller::ACTIVE_ARRAY) ? true : false,
                ];
            }
            $errors = [];
            foreach ($companiesArray as $company) {
                $validator = Validator::make($company, [
                    'name'          => ['required', 'string', 'min:3', 'max:100', Rule::unique('companies', 'name')->whereNull('deleted_at')],
                    'color'         => ['required', 'string', new WithOutSpaces],
                    'is_active'     => ['nullable', 'boolean'],
                    'remarks'       => ['string', 'nullable'],
                ]);

                if ($validator->fails()) {
                    $errors[$company['name']] = $validator->errors();
                }
            }
            if ($errors) {
                return $this->error(['errors' => $errors], Response::HTTP_UNPROCESSABLE_ENTITY);
            }
            Company::upsert($companiesArray, ['email']);
            return $this->success(__('status.imported_csv_successfully'));
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->error(__('status.import_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            return $this->error(__('status.import_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
