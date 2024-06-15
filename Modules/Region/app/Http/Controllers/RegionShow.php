<?php

namespace Modules\Region\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
use Modules\Region\Models\Region;
use Modules\Region\Transformers\RegionResource;

class RegionShow extends Controller
{
    public function __invoke(Region $region)
    {
        if (!auth()->user()->is_owner)  abort_if(Gate::denies('show-region'), Response::HTTP_FORBIDDEN, __('permission::messages.gate_denies'));
        return RegionResource::make($region->load('media'));
    }
}
