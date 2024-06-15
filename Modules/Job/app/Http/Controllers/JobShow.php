<?php

namespace Modules\Job\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
use Modules\Job\Models\Job;
use Modules\Job\Transformers\JobResource;

class JobShow extends Controller
{
    public function __invoke(Job $job)
    {
        if (!auth()->user()->is_owner)  abort_if(Gate::denies('show-job'), Response::HTTP_FORBIDDEN, __('permission::messages.gate_denies'));
        return JobResource::make($job->load('media'));
    }
}
