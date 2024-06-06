<?php

namespace Modules\Delegate\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
use Modules\Delegate\Models\Delegate;

class DelegateDestroy extends Controller
{
    public function __invoke(Delegate $delegate)
    {
        if (!auth()->user()->is_owner)  abort_if(Gate::denies('delete-delegate'), Response::HTTP_FORBIDDEN, __('permission::messages.gate_denies'));
        try {
            $delegate->delete();
            return $this->success(__('status.deleted', ['name' => $delegate->fullname, 'module' => __('modules.delegate')]));
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->error(__('status.delete_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            return $this->error(__('status.delete_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
