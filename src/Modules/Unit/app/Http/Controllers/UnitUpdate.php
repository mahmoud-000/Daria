<?php

namespace Modules\Unit\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use Modules\Unit\Models\Unit;
use Modules\Unit\Http\Requests\UpdateUnitRequest;

class UnitUpdate extends Controller
{
    public function __invoke(UpdateUnitRequest $request, Unit $unit)
    {
        try {
            $unit = DB::transaction(function () use ($unit, $request) {
                $unit->update($request->validated());

                return $unit;
            });
            return $this->success(__('status.updated', ['name' => $unit['name'], 'module' => __('modules.unit')]));
        } catch (\Illuminate\Database\QueryException $e) {
            $this->error(__('status.update_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            $this->error(trans('status.update_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
