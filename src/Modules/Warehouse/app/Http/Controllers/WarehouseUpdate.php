<?php

namespace Modules\Warehouse\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use Modules\Warehouse\Models\Warehouse;
use Modules\Warehouse\Http\Requests\UpdateWarehouseRequest;

class WarehouseUpdate extends Controller
{
    public function __invoke(UpdateWarehouseRequest $request, Warehouse $warehouse)
    {
        try {
            $warehouse = DB::transaction(function () use ($warehouse, $request) {
                $warehouse->update($request->validated());
                
                return $warehouse;
            });
            return $this->success(__('status.updated', ['name' => $warehouse['name'], 'module' => __('modules.warehouse')]));
        } catch (\Illuminate\Database\QueryException $e) {
            $this->error(__('status.update_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            $this->error(trans('status.update_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
