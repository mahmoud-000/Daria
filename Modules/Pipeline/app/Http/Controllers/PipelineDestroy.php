<?php

namespace Modules\Pipeline\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
use Modules\Pipeline\Models\Pipeline;

class PipelineDestroy extends Controller
{
    public function __invoke(Pipeline $pipeline)
    {
        if (!auth()->user()->is_owner)  abort_if(Gate::denies('delete-pipeline'), Response::HTTP_FORBIDDEN, __('permission::messages.gate_denies'));
        try {
            $pipeline->delete();
            return $this->success(__('status.deleted', ['name' => $pipeline->name, 'module' => __('modules.pipeline')]));
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->error(__('status.delete_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            return $this->error(__('status.delete_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
