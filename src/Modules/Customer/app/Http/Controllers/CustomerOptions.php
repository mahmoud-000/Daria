<?php

namespace Modules\Customer\Http\Controllers;

use App\Enums\ActiveEnum;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Customer\Transformers\CustomerResource;
use Modules\Customer\Models\Customer;

class CustomerOptions extends Controller
{
    public function __invoke(Request $req)
    {
        return CustomerResource::collection(
            Customer::query()
                ->with('media')
                ->where('is_active', ActiveEnum::ACTIVED->value)
                ->when(!empty($req->search), fn ($query) => $query->where('fullname', 'LIKE', '%' . $req->search . '%'))
                ->paginate(10)
        );
    }
}
