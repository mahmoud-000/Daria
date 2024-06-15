<?php

namespace Modules\Region\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
use Modules\Region\Models\Region;

class RegionDestroy extends Controller
{
    public function __invoke(Region $region)
    {
        if (!auth()->user()->is_owner)  abort_if(Gate::denies('delete-region'), Response::HTTP_FORBIDDEN, __('permission::messages.gate_denies'));
        try {
            $region->delete();
            return $this->success(__('status.deleted', ['name' => $region->name, 'module' => __('modules.region')]));
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->error(__('status.delete_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            return $this->error(__('status.delete_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
