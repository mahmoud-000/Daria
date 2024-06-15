<?php

namespace Modules\Job\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use Modules\Job\Models\Job;
use Modules\Job\Http\Requests\UpdateJobRequest;

class JobUpdate extends Controller
{
    public function __invoke(UpdateJobRequest $request, Job $job)
    {
        try {
            $job = DB::transaction(function () use ($job, $request) {
                $job->update($request->validated());
                
                return $job;
            });
            return $this->success(__('status.updated', ['name' => $job['title'], 'module' => __('modules.job')]));
        } catch (\Illuminate\Database\QueryException $e) {
            $this->error(__('status.update_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            $this->error(trans('status.update_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
