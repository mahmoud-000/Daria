<?php

namespace Modules\Brand\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
use Modules\Brand\Models\Brand;
use Modules\Brand\Transformers\BrandResource;

class BrandShow extends Controller
{
    public function __invoke(Brand $brand)
    {
        if (!auth()->user()->is_owner)  abort_if(Gate::denies('show-brand'), Response::HTTP_FORBIDDEN, __('permission::messages.gate_denies'));
        return BrandResource::make($brand->load('media'));
    }
}
