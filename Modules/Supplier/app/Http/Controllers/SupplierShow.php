<?php

namespace Modules\Supplier\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
use Modules\Supplier\Models\Supplier;
use Modules\Supplier\Transformers\SupplierResource;

class SupplierShow extends Controller
{
    public function __invoke(Supplier $supplier)
    {
        if (!auth()->user()->is_owner)  abort_if(!Gate::any(['show-supplier', 'edit-supplier']), Response::HTTP_FORBIDDEN, __('permission::messages.gate_denies'));
        return SupplierResource::make($supplier->load(['contacts', 'locations', 'media']));
    }
}
