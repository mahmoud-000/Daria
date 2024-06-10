<?php

namespace Modules\Attribute\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
use Modules\Attribute\Models\Attribute;
use Modules\Attribute\Transformers\AttributeResource;

class AttributeShow extends Controller
{
    public function __invoke(Attribute $attribute)
    {
        if (!auth()->user()->is_owner)  abort_if(Gate::denies('show-attribute'), Response::HTTP_FORBIDDEN, __('permission::messages.gate_denies'));
        return AttributeResource::make($attribute);
    }
}
