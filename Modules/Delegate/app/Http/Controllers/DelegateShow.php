<?php

namespace Modules\Delegate\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
use Modules\Delegate\Models\Delegate;
use Modules\Delegate\Transformers\DelegateResource;

class DelegateShow extends Controller
{
    public function __invoke(Delegate $delegate)
    {
        if (!auth()->user()->is_owner)  abort_if(!Gate::any(['show-delegate', 'edit-delegate']), Response::HTTP_FORBIDDEN, __('permission::messages.gate_denies'));
        return DelegateResource::make($delegate->load(['contacts', 'locations', 'media']));
    }
}
