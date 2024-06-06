<?php

namespace Modules\Sale\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use Modules\Sale\Entities\Sale;
use Modules\Sale\Http\Requests\UpdateSaleRequest;
use Modules\Sale\Http\Services\SaleService;

class SaleUpdate extends Controller
{
    public function __invoke(UpdateSaleRequest $request, Sale $sale, SaleService $service)
    {
        try {
            $request = $request->validated();
            $old_isComplete = $service->isComplete($sale->pipeline_id, $sale->stage_id);
            $old_invoice_effected = $sale->effected;
            $sale = DB::transaction(function () use ($sale, $request, $service, $old_isComplete, $old_invoice_effected) {
                $sale = $service->updateInvoice($sale, $request->except(['details', 'payments', 'deletedDetails', 'deletedPayments']));
                $service->updateDetails($sale, $request->details);
                $service->updateStockForOldDetails($sale, $request->details, $request->only(['pipeline_id', 'stage_id']), $old_isComplete, $old_invoice_effected);
                $service->destroyDetails($sale, $request->deletedDetails, $old_isComplete);
                $service->destroyPayments($sale, $request->deletedPayments);
                $service->createNewDetailsAndUpdateStockInUpdate($request->details, $sale, $request->only(['pipeline_id', 'stage_id']));
                $service->createPayments($sale, $request->payments);

                return $sale;
            });
            return $this->success(__('status.updated', ['name' => $sale['name'], 'module' => __('modules.sale')]));
        } catch (\Illuminate\Database\QueryException $e) {
            $this->error(__('status.update_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            $this->error(trans('status.update_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
