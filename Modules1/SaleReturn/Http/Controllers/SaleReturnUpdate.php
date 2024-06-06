<?php

namespace Modules\SaleReturn\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use Modules\SaleReturn\Entities\SaleReturn;
use Modules\SaleReturn\Http\Requests\UpdateSaleReturnRequest;
use Modules\SaleReturn\Http\Services\SaleReturnService;

class SaleReturnUpdate extends Controller
{
    public function __invoke(UpdateSaleReturnRequest $request, SaleReturn $sale_return, SaleReturnService $service)
    {
        try {
            $request = $request->validated();
            $old_isComplete = $service->isComplete($sale_return->pipeline_id, $sale_return->stage_id);
            $old_invoice_effected = $sale_return->effected;
            $sale_return = DB::transaction(function () use ($sale_return, $request, $service, $old_isComplete, $old_invoice_effected) {
                $sale_return = $service->updateInvoice($sale_return, $request->except(['details', 'payments', 'deletedDetails', 'deletedPayments']));
                $service->updateDetails($sale_return, $request->details);
                $service->updateStockForOldDetails($sale_return, $request->details, $request->only(['pipeline_id', 'stage_id']), $old_isComplete, $old_invoice_effected);
                $service->destroyDetails($sale_return, $request->deletedDetails, $old_isComplete);
                $service->destroyPayments($sale_return, $request->deletedPayments);
                $service->createNewDetailsAndUpdateStockInUpdate($request->details, $sale_return, $request->only(['pipeline_id', 'stage_id']));
                $service->createPayments($sale_return, $request->payments);

                return $sale_return;
            });
            return $this->success(__('status.updated', ['name' => $sale_return['name'], 'module' => __('modules.sale_return')]));
        } catch (\Illuminate\Database\QueryException $e) {
            $this->error(__('status.update_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            $this->error(trans('status.update_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
