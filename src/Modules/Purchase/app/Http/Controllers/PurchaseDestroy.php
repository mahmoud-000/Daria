<?php

namespace Modules\Purchase\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
use Modules\Purchase\Models\Purchase;

class PurchaseDestroy extends Controller
{
    public function __invoke(Purchase $purchase)
    {
        if (!auth()->user()->is_owner)  abort_if(Gate::denies('delete-purchase'), Response::HTTP_FORBIDDEN, __('permission::messages.gate_denies'));
        try {
            $purchase->delete();
            return $this->success(__('status.deleted', ['name' => sprintf('%07d', $purchase['id']), 'module' => __('modules.purchase')]));
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->error(__('status.delete_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            return $this->error(__('status.delete_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
