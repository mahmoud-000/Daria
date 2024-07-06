<?php

namespace Modules\Customer\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Customer\Models\Customer;
use Modules\Customer\Transformers\CustomersCollectionResource;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;

class CustomersList extends Controller
{
    public function __invoke(Request $req)
    {
        if (!auth()->user()->is_owner)  abort_if(!Gate::allows('list-customer'), Response::HTTP_FORBIDDEN, __('permission::messages.gate_denies'));
        $dir = $req->descending === 'true' ? 'desc' : 'asc';
        return CustomersCollectionResource::collection(
            Customer::query()
                ->select(['id', 'fullname', 'email', 'type', 'company_name', 'type', 'is_active', 'created_at', 'updated_at'])
                ->with(['media'])
                ->withCount(['media'])
                ->search($req->filter)
                ->orderBy($req->sortBy, $dir)
                ->paginate($req->rowsPerPage)
        );
    }
}
