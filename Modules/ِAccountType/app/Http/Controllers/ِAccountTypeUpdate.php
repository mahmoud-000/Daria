<?php

namespace Modules\ِAccountType\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use Modules\ِAccountType\Models\ِAccountType;
use Modules\ِAccountType\Http\Requests\UpdateِAccountTypeRequest;

class ِAccountTypeUpdate extends Controller
{
    public function __invoke(UpdateِAccountTypeRequest $request, ِAccountType $ِaccountType)
    {
        try {
            $ِaccountType = DB::transaction(function () use ($ِaccountType, $request) {
                $ِaccountType->update($request->validated());
                
                return $ِaccountType;
            });
            return $this->success(__('status.updated', ['name' => $warehouse['name'], 'module' => __('modules.warehouse')]));
        } catch (\Illuminate\Database\QueryException $e) {
            $this->error(__('status.update_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            $this->error(trans('status.update_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
