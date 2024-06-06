<?php

namespace Modules\Unit\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use Modules\Unit\Models\Unit;
use Modules\Unit\Http\Requests\StoreUnitRequest;

class UnitStore extends Controller
{
    public function __invoke(StoreUnitRequest $request)
    {
        try {
            $unit = DB::transaction(function () use ($request) {
                $unit = Unit::create($request->validated());

                return $unit;
            });
            return $this->success(__('status.created', ['name' => $unit['name'], 'module' => __('modules.unit')]));
        } catch (\Illuminate\Database\QueryException $e) {
            $this->error(__('status.create_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            $this->error(trans('status.create_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
