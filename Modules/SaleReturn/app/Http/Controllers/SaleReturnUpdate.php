<?php

namespace Modules\SaleReturn\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Arr;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use Modules\SaleReturn\Models\SaleReturn;
use Modules\SaleReturn\Http\Requests\UpdateSaleReturnRequest;
use Modules\SaleReturn\Http\Services\SaleReturnService;
use Modules\Upload\Http\Controllers\FilesAssign;

class SaleReturnUpdate extends Controller
{
    public function __invoke(UpdateSaleReturnRequest $request, SaleReturn $saleReturn, SaleReturnService $service)
    {
        try {
            $request = $request->validated();

            if ($service->isDuplicateDetails($request['details'])) return $this->error(__('status.details_dublicate_error'), Response::HTTP_INTERNAL_SERVER_ERROR);

            $old_isComplete = $service->isComplete($saleReturn->pipeline_id, $saleReturn->stage_id);
            $old_invoice_effected = $saleReturn->effected;
            $saleReturn = DB::transaction(function () use ($saleReturn, $request, $service, $old_isComplete, $old_invoice_effected) {
                $saleReturn = $service->updateInvoice($saleReturn, Arr::except($request, ['details', 'payments', 'saleReturn_documents', 'deletedDetails', 'deletedPayments']));

                if (isset($request['saleReturn_documents']) && !is_null($request['saleReturn_documents']) && !array_key_exists('fake', $request['saleReturn_documents'])) {
                    (new FilesAssign)($request['saleReturn_documents'], $saleReturn, 'saleReturns', 'saleReturn_documents', true);
                }

                $detailsIsset = isset($request['details']) ? $request['details'] : [];
                $deletedDetailsIsset = isset($request['deletedDetails']) ? $request['deletedDetails'] : [];

                $paymentsIdIsset = isset($request['payments']) ? $request['payments'] : [];
                $deletedPaymentsIdIsset = isset($request['deletedPayments']) ? $request['deletedPayments'] : [];

                // Update Old Details
                $service->updateDetails($saleReturn, $detailsIsset);

                // Update Old Details Stock
                $service->updateStockForOldDetails($saleReturn, $detailsIsset, Arr::only($request, ['pipeline_id', 'stage_id']), $old_isComplete, $old_invoice_effected);

                // Delete Old Details If in Deleted Details
                $service->destroyDetails($saleReturn, $deletedDetailsIsset, $old_isComplete);

                $service->destroyPayments($saleReturn, $deletedPaymentsIdIsset);

                $service->createNewDetailsAndUpdateStockInUpdate($detailsIsset, $saleReturn, Arr::only($request, ['pipeline_id', 'stage_id']));
                $service->createPayments($saleReturn, $paymentsIdIsset);

                return $saleReturn;
            });
            return $this->success(__('status.updated', ['name' => sprintf('%07d', $saleReturn['id']), 'module' => __('modules.saleReturn')]));
        } catch (\Illuminate\Database\QueryException $e) {
            $this->error(__('status.update_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            $this->error(trans('status.update_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
