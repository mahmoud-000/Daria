<?php

namespace Modules\SaleReturn\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
use Modules\SaleReturn\Models\SaleReturn;

class SaleReturnDestroy extends Controller
{
    public function __invoke(SaleReturn $saleReturn)
    {
        if (!auth()->user()->is_owner)  abort_if(Gate::denies('delete-saleReturn'), Response::HTTP_FORBIDDEN, __('permission::messages.gate_denies'));
        try {
            $saleReturn->delete();
            return $this->success(__('status.deleted', ['name' => sprintf('%07d', $saleReturn['id']), 'module' => __('modules.saleReturn')]));
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->error(__('status.delete_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            return $this->error(__('status.delete_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
