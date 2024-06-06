<?php

namespace Modules\SaleReturn\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
use Modules\SaleReturn\Entities\SaleReturn;

class SaleReturnDestroy extends Controller
{
    public function __invoke(SaleReturn $sale_return)
    {
        if (!auth()->user()->is_owner)  abort_if(Gate::denies('delete-sale_return'), Response::HTTP_FORBIDDEN, __('permission::messages.gate_denies'));
        try {
            $sale_return->delete();
            return $this->success(__('status.deleted', ['name' => $sale_return->name, 'module' => __('modules.sale_return')]));
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->error(__('status.delete_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            return $this->error(__('status.delete_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
