<?php

namespace Modules\Unit\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
use Modules\Unit\Models\Unit;

class UnitDestroy extends Controller
{
    public function __invoke(Unit $unit)
    {
        if (!auth()->user()->is_owner)  abort_if(Gate::denies('delete-unit'), Response::HTTP_FORBIDDEN, __('permission::messages.gate_denies'));
        try {
            if (!$unit->sub()->exists()) {
                $unit->delete();
                return $this->success(__('status.deleted', ['name' => $unit->name, 'module' => __('modules.unit')]));
            } else {
                return $this->error(__('status.has_relation', ['name' => $unit->name, 'module' => __('modules.unit')]), Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->error(__('status.delete_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            return $this->error(__('status.delete_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
