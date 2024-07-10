<?php

namespace Modules\PurchaseReturn\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Arr;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use Modules\PurchaseReturn\Models\PurchaseReturn;
use Modules\PurchaseReturn\Http\Requests\UpdatePurchaseReturnRequest;
use Modules\PurchaseReturn\Http\Services\PurchaseReturnService;
use Modules\Upload\Http\Controllers\FilesAssign;

class PurchaseReturnUpdate extends Controller
{
    public function __invoke(UpdatePurchaseReturnRequest $request, PurchaseReturn $purchaseReturn, PurchaseReturnService $service)
    {
        try {
            $request = $request->validated();

            if ($service->isDuplicateDetails($request['details'])) return $this->error(__('status.details_dublicate_error'), Response::HTTP_INTERNAL_SERVER_ERROR);

            $old_isComplete = $service->isComplete($purchaseReturn->pipeline_id, $purchaseReturn->stage_id);
            $old_invoice_effected = $purchaseReturn->effected;
            $purchaseReturn = DB::transaction(function () use ($purchaseReturn, $request, $service, $old_isComplete, $old_invoice_effected) {
                $purchaseReturn = $service->updateInvoice($purchaseReturn, Arr::except($request, ['details', 'payments', 'purchaseReturn_documents', 'deletedDetails', 'deletedPayments']));

                if (isset($request['purchaseReturn_documents']) && !is_null($request['purchaseReturn_documents']) && !array_key_exists('fake', $request['purchaseReturn_documents'])) {
                    (new FilesAssign)($request['purchaseReturn_documents'], $purchaseReturn, 'purchaseReturns', 'purchaseReturn_documents', true);
                }

                $detailsIsset = isset($request['details']) ? $request['details'] : [];
                $deletedDetailsIsset = isset($request['deletedDetails']) ? $request['deletedDetails'] : [];

                $paymentsIdIsset = isset($request['payments']) ? $request['payments'] : [];
                $deletedPaymentsIdIsset = isset($request['deletedPayments']) ? $request['deletedPayments'] : [];

                // Update Old Details
                $service->updateDetails($purchaseReturn, $detailsIsset);

                // Update Old Details Stock
                $service->updateStockForOldDetails($purchaseReturn, $detailsIsset, Arr::only($request, ['pipeline_id', 'stage_id']), $old_isComplete, $old_invoice_effected);

                // Delete Old Details If in Deleted Details
                $service->destroyDetails($purchaseReturn, $deletedDetailsIsset, $old_isComplete);

                $service->destroyPayments($purchaseReturn, $deletedPaymentsIdIsset);

                $service->createNewDetailsAndUpdateStockInUpdate($detailsIsset, $purchaseReturn, Arr::only($request, ['pipeline_id', 'stage_id']));
                $service->createPayments($purchaseReturn, $paymentsIdIsset);

                return $purchaseReturn;
            });
            return $this->success(__('status.updated', ['name' => sprintf('%07d', $purchaseReturn['id']), 'module' => __('modules.purchaseReturn')]));
        } catch (\Illuminate\Database\QueryException $e) {
            $this->error(__('status.update_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            $this->error(trans('status.update_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
