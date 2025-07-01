<?php

namespace Modules\Variant\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
use Modules\Variant\Models\Variant;

class VariantDestroy extends Controller
{
    public function __invoke(Variant $variant)
    {
        if (!auth()->user()->is_owner)  abort_if(Gate::denies('delete-variant'), Response::HTTP_FORBIDDEN, __('permission::messages.gate_denies'));
        try {
            $variant->delete();
            return $this->success(__('status.deleted', ['name' => $variant->name, 'module' => __('modules.variant')]));
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->error(__('status.delete_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            return $this->error(__('status.delete_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
