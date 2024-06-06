<?php

namespace Modules\Brand\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Modules\Brand\Models\Brand;
use Illuminate\Support\Str;
class BrandImportCsv extends Controller
{
    public function __invoke(Request $request)
    {
        if (!auth()->user()->is_owner)  abort_if(Gate::denies('import-csv-brand'), Response::HTTP_FORBIDDEN, __('permission::messages.gate_denies'));
        try {
            $brands = $request->all();
            $removed = array_shift($brands);
            $brandsArray = [];
            foreach ($brands as $key => $value) {
                $brandsArray[] = [
                    'name' => $value['name'],
                    'is_active' => isset($value['is_active']) && in_array(Str::lower($value['is_active']), Controller::ACTIVE_ARRAY) ? true : false,
                ];
            }
            $errors = [];
            foreach ($brandsArray as $brand) {
                $validator = Validator::make($brand, [
                    'name'          => ['required', 'string', 'min:3', 'max:100', Rule::unique('brands', 'name')->whereNull('deleted_at')],
                    'is_active'     => ['nullable', 'boolean'],
                    'remarks'       => ['string', 'nullable'],
                ]);

                if ($validator->fails()) {
                    $errors[$brand['name']] = $validator->errors();
                }
            }
            if ($errors) {
                return $this->error(['errors' => $errors], Response::HTTP_UNPROCESSABLE_ENTITY);
            }
            Brand::upsert($brandsArray, ['email']);
            return $this->success(__('status.imported_csv_successfully'));
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->error(__('status.import_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            return $this->error(__('status.import_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
