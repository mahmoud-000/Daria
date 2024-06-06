<?php

namespace Modules\Customer\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Customer\Transformers\CustomerResource;
use Modules\Customer\Models\Customer;

class CustomerOptions extends Controller
{
    public function __invoke()
    {
        return CustomerResource::collection(Customer::with('media')->where('is_active', true)->get());
    }
}
