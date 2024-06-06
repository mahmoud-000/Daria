<?php

namespace Modules\Sale\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Modules\Sale\Entities\Sale;
use Modules\Sale\Http\Requests\StoreSaleRequest;
use Modules\Sale\Http\Services\SaleService;

class SaleStore extends Controller
{
    public function __invoke(StoreSaleRequest $request, SaleService $service)
    {
        try {
            $request = $request->validated();
            $sale = DB::transaction(function () use ($request, $service) {
                $isComplete = $service->isComplete($request['pipeline_id'], $request['stage_id']);

                $sale = Sale::create(Arr::except($request, ['details', 'payments']) + ['effected' => $isComplete]);

                $service->createDetails($sale, $request->details);
                $service->updateStockInCreate($sale, $request->details, $isComplete);
                $service->createPayments($sale, $request['payments']);

                return $sale;
            });
            return $this->success(__('status.created', ['name' => $sale['name'], 'module' => __('modules.sale')]));
        } catch (\Illuminate\Database\QueryException $e) {
            $this->error(__('status.create_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            $this->error(trans('status.create_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
