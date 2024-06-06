<?php

namespace Modules\Customer\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
use Modules\Customer\Models\Customer;

class CustomerDestroy extends Controller
{
    public function __invoke(Customer $customer)
    {
        if (!auth()->user()->is_owner)  abort_if(Gate::denies('delete-customer'), Response::HTTP_FORBIDDEN, __('permission::messages.gate_denies'));
        try {
            $customer->delete();
            return $this->success(__('status.deleted', ['name' => $customer->fullname, 'module' => __('modules.customer')]));
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->error(__('status.delete_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            return $this->error(__('status.delete_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
