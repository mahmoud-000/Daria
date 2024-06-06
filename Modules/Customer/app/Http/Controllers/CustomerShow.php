<?php

namespace Modules\Customer\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
use Modules\Customer\Models\Customer;
use Modules\Customer\Transformers\CustomerResource;

class CustomerShow extends Controller
{
    public function __invoke(Customer $customer)
    {
        if (!auth()->user()->is_owner)  abort_if(!Gate::any(['show-customer', 'edit-customer']), Response::HTTP_FORBIDDEN, __('permission::messages.gate_denies'));
        return CustomerResource::make($customer->load(['contacts', 'locations', 'media']));
    }
}
