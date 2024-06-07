<?php

namespace Modules\Branch\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
use Modules\Branch\Models\Branch;

class BranchDestroy extends Controller
{
    public function __invoke(Branch $branch)
    {
        if (!auth()->user()->is_owner)  abort_if(Gate::denies('delete-branch'), Response::HTTP_FORBIDDEN, __('permission::messages.gate_denies'));
        if($branch->is_main) return $this->error(__('status.delete_main_branch_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        try {
            $branch->delete();
            return $this->success(__('status.deleted', ['name' => $branch->name, 'module' => __('modules.branch')]));
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->error(__('status.delete_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            return $this->error(__('status.delete_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
