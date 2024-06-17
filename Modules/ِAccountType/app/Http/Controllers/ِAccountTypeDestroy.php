<?php

namespace Modules\ِAccountType\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
use Modules\ِAccountType\Models\ِAccountType;

class ِAccountTypeDestroy extends Controller
{
    public function __invoke(ِAccountType $ِaccountType)
    {
        if (!auth()->user()->is_owner)  abort_if(Gate::denies('delete-ِaccountType'), Response::HTTP_FORBIDDEN, __('permission::messages.gate_denies'));
        try {
            $ِaccountType->delete();
            return $this->success(__('status.deleted', ['name' => $ِaccountType->name, 'module' => __('modules.ِaccountType')]));
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->error(__('status.delete_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            return $this->error(__('status.delete_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
