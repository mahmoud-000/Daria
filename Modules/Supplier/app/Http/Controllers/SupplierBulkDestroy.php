<?php

namespace Modules\Supplier\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
use Modules\Supplier\Models\Supplier;

class SupplierBulkDestroy extends Controller
{
    public function __invoke(Request $request)
    {
        if (!auth()->user()->is_owner)  abort_if(Gate::denies('bulk-delete-supplier'), Response::HTTP_FORBIDDEN, __('permission::messages.gate_denies'));
        try {
            Supplier::whereIn('id', $request->ids)->delete();
            // $supplier->permissions()->detach();
            // $supplier->roles()->detach();
            // $supplier->contacts()->delete();
            // $supplier->locations()->delete();
            // $supplier->media()->delete();
            return $this->success(__('status.deleted_selected_success'));
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->error(__('status.delete_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            return $this->error(__('status.delete_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
