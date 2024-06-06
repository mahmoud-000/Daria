<?php

namespace Modules\Supplier\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
use Modules\Supplier\Models\Supplier;

class SupplierDestroy extends Controller
{
    public function __invoke(Supplier $supplier)
    {
        if (!auth()->user()->is_owner)  abort_if(Gate::denies('delete-supplier'), Response::HTTP_FORBIDDEN, __('permission::messages.gate_denies'));
        try {
            $supplier->delete();
            return $this->success(__('status.deleted', ['name' => $supplier->fullname, 'module' => __('modules.supplier')]));
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->error(__('status.delete_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            return $this->error(__('status.delete_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
