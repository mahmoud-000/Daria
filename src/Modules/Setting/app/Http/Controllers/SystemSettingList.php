<?php

namespace Modules\Setting\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\Setting\Models\Setting;
use Modules\Setting\Transformers\SettingResource;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;

class SystemSettingList extends Controller
{
    public function __invoke()
    {
        if (!auth()->user()->is_owner)  abort_if(!Gate::allows('list-system-settings'), Response::HTTP_FORBIDDEN, __('permission::messages.gate_denies'));
        return SettingResource::collection(Setting::systemOnly()->get());
    }
}
