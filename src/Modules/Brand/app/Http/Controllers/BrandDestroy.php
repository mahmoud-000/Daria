<?php

namespace Modules\Brand\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
use Modules\Brand\Models\Brand;

class BrandDestroy extends Controller
{
    public function __invoke(Brand $brand)
    {
        if (!auth()->user()->is_owner)  abort_if(Gate::denies('delete-brand'), Response::HTTP_FORBIDDEN, __('permission::messages.gate_denies'));
        try {
            $brand->delete();
            return $this->success(__('status.deleted', ['name' => $brand->name, 'module' => __('modules.brand')]));
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->error(__('status.delete_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            return $this->error(__('status.delete_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
