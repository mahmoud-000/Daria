<?php

namespace Modules\Job\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use Modules\Job\Models\Job;
use Modules\Job\Http\Requests\StoreJobRequest;

class JobStore extends Controller
{
    public function __invoke(StoreJobRequest $request)
    {
        try {
            $job = DB::transaction(function () use ($request) {
                $job = Job::create($request->validated());

                return $job;
            });
            return $this->success(__('status.created', ['name' => $job['title'], 'module' => __('modules.job')]));
        } catch (\Illuminate\Database\QueryException $e) {
            $this->error(__('status.create_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            $this->error(trans('status.create_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
