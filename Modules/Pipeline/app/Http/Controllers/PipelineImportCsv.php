<?php

namespace Modules\Pipeline\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Rules\WithOutSpaces;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Modules\Pipeline\Models\Pipeline;
use Illuminate\Support\Str;
class PipelineImportCsv extends Controller
{
    public function __invoke(Request $request)
    {
        if (!auth()->user()->is_owner)  abort_if(Gate::denies('import-csv-pipeline'), Response::HTTP_FORBIDDEN, __('permission::messages.gate_denies'));
        try {
            $pipelines = $request->all();
            $removed = array_shift($pipelines);
            $pipelinesArray = [];
            foreach ($pipelines as $key => $value) {
                $pipelinesArray[] = [
                    'name' => $value['name'],
                    'color' => isset($value['color']) ? $value['color'] : Controller::MAIN_COLOR,
                    'is_active' => isset($value['is_active']) && in_array(Str::lower($value['is_active']), Controller::ACTIVE_ARRAY) ? true : false,
                ];
            }
            $errors = [];
            foreach ($pipelinesArray as $pipeline) {
                $validator = Validator::make($pipeline, [
                    'name'          => ['required', 'string', 'min:3', 'max:100', Rule::unique('pipelines', 'name')->withoutTrashed()],
                    'color'         => ['required', 'string', new WithOutSpaces],
                    'is_active'     => ['required', 'boolean'],
                    'remarks'       => ['string', 'nullable'],
                ]);

                if ($validator->fails()) {
                    $errors[$pipeline['name']] = $validator->errors();
                }
            }
            if ($errors) {
                return $this->error(['errors' => $errors], Response::HTTP_UNPROCESSABLE_ENTITY);
            }
            Pipeline::upsert($pipelinesArray, ['email']);
            return $this->success(__('status.imported_csv_successfully'));
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->error(__('status.import_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            return $this->error(__('status.import_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
