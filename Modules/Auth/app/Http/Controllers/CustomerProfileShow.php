<?php

namespace Modules\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
use Modules\Auth\Transformers\CustomerProfileResource;
use Modules\Customer\Models\Customer;

class CustomerProfileShow extends Controller
{
    public function __invoke()
    {

        if (!auth()->user()->is_owner)  abort_if(!Gate::any(['show-customer-profile', 'edit-customer-profile']), Response::HTTP_FORBIDDEN, __('permission::messages.gate_denies'));
        $profile = Customer::with(['contacts', 'locations', 'media'])
            ->where('id', auth()->id())
            ->first();
        return CustomerProfileResource::make($profile);
    }
}
