<?php

namespace Modules\PurchaseReturn\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
use Modules\PurchaseReturn\Models\PurchaseReturn;

class PurchaseReturnDestroy extends Controller
{
    public function __invoke(PurchaseReturn $purchaseReturn)
    {
        if (!auth()->user()->is_owner)  abort_if(Gate::denies('delete-purchaseReturn'), Response::HTTP_FORBIDDEN, __('permission::messages.gate_denies'));
        try {
            $purchaseReturn->delete();
            return $this->success(__('status.deleted', ['name' => sprintf('%07d', $purchaseReturn['id']), 'module' => __('modules.purchaseReturn')]));
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->error(__('status.delete_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            return $this->error(__('status.delete_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
