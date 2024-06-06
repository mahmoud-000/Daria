<?php

namespace Modules\Sale\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
use Modules\Sale\Entities\Sale;

class SaleDestroy extends Controller
{
    public function __invoke(Sale $sale)
    {
        if (!auth()->user()->is_owner)  abort_if(Gate::denies('delete-sale'), Response::HTTP_FORBIDDEN, __('permission::messages.gate_denies'));
        try {
            $sale->delete();
            return $this->success(__('status.deleted', ['name' => $sale->name, 'module' => __('modules.sale')]));
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->error(__('status.delete_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            return $this->error(__('status.delete_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
