<?php

namespace Modules\Stage\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
use Modules\Stage\Models\Stage;

class StageDestroy extends Controller
{
    public function __invoke(Stage $stage)
    {
        if (!auth()->user()->is_owner)  abort_if(Gate::denies('delete-stage'), Response::HTTP_FORBIDDEN, __('permission::messages.gate_denies'));
        try {
            $stage->delete();
            return $this->success(__('status.deleted', ['name' => $stage->name, 'module' => __('modules.stage')]));
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->error(__('status.delete_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            return $this->error(__('status.delete_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
