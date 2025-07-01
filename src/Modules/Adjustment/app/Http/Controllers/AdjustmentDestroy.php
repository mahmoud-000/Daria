<?php

namespace Modules\Adjustment\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
use Modules\Adjustment\Models\Adjustment;

class AdjustmentDestroy extends Controller
{
    public function __invoke(Adjustment $adjustment)
    {
        if (!auth()->user()->is_owner)  abort_if(Gate::denies('delete-adjustment'), Response::HTTP_FORBIDDEN, __('permission::messages.gate_denies'));
        try {
            $adjustment->delete();
            return $this->success(__('status.deleted', ['name' => sprintf('%07d', $adjustment['id']), 'module' => __('modules.adjustment')]));
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->error(__('status.delete_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            return $this->error(__('status.delete_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
