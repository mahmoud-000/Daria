<?php

namespace Modules\Job\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
use Modules\Job\Models\Job;

class JobDestroy extends Controller
{
    public function __invoke(Job $job)
    {
        if (!auth()->user()->is_owner)  abort_if(Gate::denies('delete-job'), Response::HTTP_FORBIDDEN, __('permission::messages.gate_denies'));
        try {
            $job->delete();
            return $this->success(__('status.deleted', ['name' => $job->title, 'module' => __('modules.job')]));
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->error(__('status.delete_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            return $this->error(__('status.delete_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
