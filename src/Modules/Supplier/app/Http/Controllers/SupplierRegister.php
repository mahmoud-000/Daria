<?php

namespace Modules\Supplier\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Modules\Upload\Http\Controllers\FilesAssign;
use Modules\Supplier\Models\Supplier;
use Modules\Supplier\Http\Requests\RegisterSupplierRequest;

class SupplierRegister extends Controller
{
    public function __invoke(RegisterSupplierRequest $request)
    {
        try {
            $request = $request->validated();
            $supplier = DB::transaction(function () use ($request) {
                $supplier = Supplier::create(Arr::except($request, ['contacts', 'locations', 'logo']));

                if (isset($request['contacts']) && count($request['contacts'])) {
                    $supplier->contacts()->createMany($request['contacts']);
                }

                if (isset($request['locations']) && count($request['locations'])) {
                    $supplier->locations()->createMany($request['locations']);
                }

                if (isset($request['logo']) && !is_null($request['logo']) && !array_key_exists('fake', $request['logo'])) {
                    (new FilesAssign)($request['logo'], $supplier, 'suppliers', 'logo');
                }

                return $supplier;
            });
            return $this->success(__('status.created', ['name' => $supplier['username'], 'module' => __('modules.supplier')]));
        } catch (\Illuminate\Database\QueryException $e) {
            $this->error(__('status.create_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            $this->error(trans('status.create_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
