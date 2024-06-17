<?php

namespace Modules\ِAccountType\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
use Modules\ِAccountType\Models\ِAccountType;
use Modules\ِAccountType\Transformers\ِAccountTypeResource;

class ِAccountTypeShow extends Controller
{
    public function __invoke(ِAccountType $ِaccountType)
    {
        if (!auth()->user()->is_owner)  abort_if(Gate::denies('show-ِaccountType'), Response::HTTP_FORBIDDEN, __('permission::messages.gate_denies'));
        return ِAccountTypeResource::make($ِaccountType);
    }
}
