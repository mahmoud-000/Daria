<?php

namespace Modules\Customer\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Modules\Upload\Http\Controllers\FilesAssign;
use Modules\Customer\Models\Customer;
use Modules\Customer\Http\Requests\RegisterCustomerRequest;

class CustomerRegister extends Controller
{
    public function __invoke(RegisterCustomerRequest $request)
    {
        try {
            $request = $request->validated();
            $customer = DB::transaction(function () use ($request) {
                $customer = Customer::create(Arr::except($request, ['contacts', 'locations', 'logo']));

                if (isset($request['contacts']) && count($request['contacts'])) {
                    $customer->contacts()->createMany($request['contacts']);
                }

                if (isset($request['locations']) && count($request['locations'])) {
                    $customer->locations()->createMany($request['locations']);
                }

                if (isset($request['logo']) && !is_null($request['logo']) && !array_key_exists('fake', $request['logo'])) {
                    (new FilesAssign)($request['logo'], $customer, 'customers', 'logo');
                }

                return $customer;
            });
            return $this->success(__('status.created', ['name' => $customer['username'], 'module' => __('modules.customer')]));
        } catch (\Illuminate\Database\QueryException $e) {
            $this->error(__('status.create_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            $this->error(trans('status.create_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
