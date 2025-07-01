<?php

namespace Modules\Supplier\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Modules\Upload\Http\Controllers\FilesAssign;
use Modules\Supplier\Models\Supplier;
use Modules\Supplier\Http\Requests\StoreSupplierRequest;

class SupplierStore extends Controller
{
    public function __invoke(StoreSupplierRequest $request)
    {
        try {
            $request = $request->validated();
            $supplier = DB::transaction(function () use ($request) {
                $supplier = Supplier::create(Arr::except($request, ['contacts', 'locations', 'avatar']));

                if (isset($request['contacts']) && count($request['contacts'])) {
                    $supplier->contacts()->createMany($request['contacts']);
                }

                if (isset($request['locations']) && count($request['locations'])) {
                    $supplier->locations()->createMany($request['locations']);
                }

                if (isset($request['avatar']) && !is_null($request['avatar']) && !array_key_exists('fake', $request['avatar'])) {
                    (new FilesAssign)($request['avatar'], $supplier, 'suppliers', 'avatar');
                }
 
                return $supplier;
            });
            return $this->success(__('status.created', ['name' => $supplier['fullname'], 'module' => __('modules.supplier')]));
        } catch (\Illuminate\Database\QueryException $e) {
            $this->error(__('status.create_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            $this->error(trans('status.create_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
