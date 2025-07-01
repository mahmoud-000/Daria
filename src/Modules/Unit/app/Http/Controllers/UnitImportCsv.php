<?php

namespace Modules\Unit\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Modules\Unit\Models\Unit;
use Illuminate\Support\Str;
class UnitImportCsv extends Controller
{
    public function __invoke(Request $request)
    {
        if (!auth()->user()->is_owner)  abort_if(Gate::denies('import-csv-unit'), Response::HTTP_FORBIDDEN, __('permission::messages.gate_denies'));
        try {
            $units = $request->all();
            $removed = array_shift($units);
            $allUnits = Unit::where('is_active', true)->get();
            $unitsArray = [];
            foreach ($units as $key => $value) {
                $unitsArray[] = [
                    'name' => $value['name'],
                    'short_name' => $value['short_name'],
                    'operator' => isset($value['operator']) && in_array($value['operator'], ['/', '*']) ? $value['operator'] : null,
                    'operator_value' => isset($value['operator_value']) ? $value['operator_value'] : null,
                    'unit_id' => isset($value['unit_id']) && $allUnits->where('id', $value['unit_id'])->first()->id ? $value['unit_id'] : null,
                    'is_active' => isset($value['is_active']) && in_array(Str::lower($value['is_active']), Controller::ACTIVE_ARRAY) ? true : false,
                ];
            }
            $errors = [];
            foreach ($unitsArray as $unit) {
                $validator = Validator::make($unit, [
                    'name'          => ['required', 'string', 'min:3', 'max:100', Rule::unique('units', 'name')->withoutTrashed()],
                    'short_name'    => ['required', 'min:1', 'max:50', Rule::unique('units', 'short_name')->withoutTrashed()],
                    'unit_id'       => ['sometimes', 'integer', 'nullable'],
                    'operator'      => [
                        'sometimes', 'nullable',
                        Rule::in(['*', '/']), Rule::requiredIf($unit['unit_id'])
                    ],
                    'operator_value' => [
                        'sometimes', 'integer', 'nullable',
                        Rule::requiredIf($unit['unit_id'])
                    ],
                    'is_active'     => ['required', 'boolean'],
                    'remarks'       => ['string', 'nullable'],
                ]);

                if ($validator->fails()) {
                    $errors[$unit['name']] = $validator->errors();
                }
            }
            if ($errors) {
                return $this->error(['errors' => $errors], Response::HTTP_UNPROCESSABLE_ENTITY);
            }
            Unit::upsert($unitsArray, ['email']);
            return $this->success(__('status.imported_csv_successfully'));
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->error(__('status.import_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            return $this->error(__('status.import_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
