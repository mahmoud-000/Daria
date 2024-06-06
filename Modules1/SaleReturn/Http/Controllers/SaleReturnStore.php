<?php

namespace Modules\SaleReturn\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Modules\SaleReturn\Entities\SaleReturn;
use Modules\SaleReturn\Http\Requests\StoreSaleReturnRequest;
use Modules\SaleReturn\Http\Services\SaleReturnService;

class SaleReturnStore extends Controller
{
    public function __invoke(StoreSaleReturnRequest $request, SaleReturnService $service)
    {
        try {
            $request = $request->validated();
            $sale_return = DB::transaction(function () use ($request, $service) {
                $isComplete = $service->isComplete($request['pipeline_id'], $request['stage_id']);

                $sale_return = SaleReturn::create(Arr::except($request, ['details', 'payments']) + ['effected' => $isComplete]);

                $service->createDetails($sale_return, $request->details);
                $service->updateStockInCreate($sale_return, $request->details, $isComplete);
                $service->createPayments($sale_return, $request['payments']);

                return $sale_return;
            });
            return $this->success(__('status.created', ['name' => $sale_return['name'], 'module' => __('modules.sale_return')]));
        } catch (\Illuminate\Database\QueryException $e) {
            $this->error(__('status.create_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            $this->error(trans('status.create_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
