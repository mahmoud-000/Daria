<?php

namespace Modules\Warehouse\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
use Modules\Warehouse\Models\Warehouse;

class WarehouseDestroy extends Controller
{
    public function __invoke(Warehouse $warehouse)
    {
        if (!auth()->user()->is_owner)  abort_if(Gate::denies('delete-warehouse'), Response::HTTP_FORBIDDEN, __('permission::messages.gate_denies'));
        try {
            $warehouse->delete();
            return $this->success(__('status.deleted', ['name' => $warehouse->name, 'module' => __('modules.warehouse')]));
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->error(__('status.delete_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            return $this->error(__('status.delete_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
