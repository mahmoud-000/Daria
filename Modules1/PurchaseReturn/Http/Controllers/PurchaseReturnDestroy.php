<?php

namespace Modules\PurchaseReturn\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
use Modules\PurchaseReturn\Entities\PurchaseReturn;

class PurchaseReturnDestroy extends Controller
{
    public function __invoke(PurchaseReturn $purchase_return)
    {
        if (!auth()->user()->is_owner)  abort_if(Gate::denies('delete-purchase_return'), Response::HTTP_FORBIDDEN, __('permission::messages.gate_denies'));
        try {
            $purchase_return->delete();
            return $this->success(__('status.deleted', ['name' => $purchase_return->name, 'module' => __('modules.purchase_return')]));
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->error(__('status.delete_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            return $this->error(__('status.delete_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
