<?php

namespace Modules\PurchaseReturn\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use Modules\PurchaseReturn\Entities\PurchaseReturn;
use Modules\PurchaseReturn\Http\Requests\UpdatePurchaseReturnRequest;
use Modules\PurchaseReturn\Http\Services\PurchaseReturnService;

class PurchaseReturnUpdate extends Controller
{
    public function __invoke(UpdatePurchaseReturnRequest $request, PurchaseReturn $purchase_return, PurchaseReturnService $service)
    {
        try {
            $request = $request->validated();
            $old_isComplete = $service->isComplete($purchase_return->pipeline_id, $purchase_return->stage_id);
            $old_invoice_effected = $purchase_return->effected;
            $purchase_return = DB::transaction(function () use ($purchase_return, $request, $service, $old_isComplete, $old_invoice_effected) {
                $purchase_return = $service->updateInvoice($purchase_return, $request->except(['details', 'payments', 'deletedDetails', 'deletedPayments']));
                $service->updateDetails($purchase_return, $request->details);
                $service->updateStockForOldDetails($purchase_return, $request->details, $request->only(['pipeline_id', 'stage_id']), $old_isComplete, $old_invoice_effected);
                $service->destroyDetails($purchase_return, $request->deletedDetails, $old_isComplete);
                $service->destroyPayments($purchase_return, $request->deletedPayments);
                $service->createNewDetailsAndUpdateStockInUpdate($request->details, $purchase_return, $request->only(['pipeline_id', 'stage_id']));
                $service->createPayments($purchase_return, $request->payments);

                return $purchase_return;
            });
            return $this->success(__('status.updated', ['name' => $purchase_return['name'], 'module' => __('modules.purchase_return')]));
        } catch (\Illuminate\Database\QueryException $e) {
            $this->error(__('status.update_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            $this->error(trans('status.update_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
