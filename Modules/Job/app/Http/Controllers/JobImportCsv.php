<?php

namespace Modules\Job\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Modules\Job\Models\Job;
use Illuminate\Support\Str;
class JobImportCsv extends Controller
{
    public function __invoke(Request $request)
    {
        if (!auth()->user()->is_owner)  abort_if(Gate::denies('import-csv-job'), Response::HTTP_FORBIDDEN, __('permission::messages.gate_denies'));
        try {
            $jobs = $request->all();
            $removed = array_shift($jobs);
            $jobsArray = [];
            foreach ($jobs as $key => $value) {
                $jobsArray[] = [
                    'name' => $value['name'],
                    'is_active' => isset($value['is_active']) && in_array(Str::lower($value['is_active']), Controller::ACTIVE_ARRAY) ? true : false,
                ];
            }
            $errors = [];
            foreach ($jobsArray as $job) {
                $validator = Validator::make($job, [
                    'name'          => ['required', 'string', 'min:3', 'max:100', Rule::unique('jobs', 'name')->withoutTrashed()],
                    'is_active'     => ['required', 'boolean'],
                    'remarks'       => ['string', 'nullable'],
                ]);

                if ($validator->fails()) {
                    $errors[$job['name']] = $validator->errors();
                }
            }
            if ($errors) {
                return $this->error(['errors' => $errors], Response::HTTP_UNPROCESSABLE_ENTITY);
            }
            Job::upsert($jobsArray, ['email']);
            return $this->success(__('status.imported_csv_successfully'));
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->error(__('status.import_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            return $this->error(__('status.import_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
