<?php

namespace Modules\Purchase\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Arr;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use Modules\Purchase\Models\Purchase;
use Modules\Purchase\Http\Requests\UpdatePurchaseRequest;
use Modules\Purchase\Http\Services\PurchaseService;
use Modules\Upload\Http\Controllers\FilesAssign;

class PurchaseUpdate extends Controller
{
    public function __invoke(UpdatePurchaseRequest $request, Purchase $purchase, PurchaseService $service)
    {
        try {
            $request = $request->validated();

            if ($service->isDuplicateDetails($request['details'])) return $this->error(__('status.details_dublicate_error'), Response::HTTP_INTERNAL_SERVER_ERROR);

            $purchase = DB::transaction(function () use ($purchase, $request, $service) {
                $purchase = $service->updateInvoice($purchase, Arr::except($request, ['details', 'payments', 'purchase_documents', 'deletedDetails', 'deletedPayments']));

                if (isset($request['purchase_documents']) && !is_null($request['purchase_documents']) && !array_key_exists('fake', $request['purchase_documents'])) {
                    (new FilesAssign)($request['purchase_documents'], $purchase, 'purchases', 'purchase_documents', true);
                }

                $detailsIsset = isset($request['details']) ? $request['details'] : [];
                $deletedDetailsIsset = isset($request['deletedDetails']) ? $request['deletedDetails'] : [];

                $paymentsIsset = isset($request['payments']) ? $request['payments'] : [];
                $deletedPaymentsIdIsset = isset($request['deletedPayments']) ? $request['deletedPayments'] : [];

                // Update Old Details
                $service->updateDetails($purchase, $detailsIsset);

                // Update Old Details Stock
                $service->updateStockForOldDetails($purchase, $detailsIsset, $request['stage_id']);

                // Delete Old Details If in Deleted Details
                $service->destroyDetails($purchase, $deletedDetailsIsset);

                $service->destroyPayments($purchase, $deletedPaymentsIdIsset);

                $service->createNewDetailsAndUpdateStockInUpdate($detailsIsset, $purchase, $request['stage_id']);
                $service->createPayments($purchase, $paymentsIsset);

                return $purchase;
            });
            return $this->success(__('status.updated', ['name' => sprintf('%07d', $purchase['id']), 'module' => __('modules.purchase')]));
        } catch (\Illuminate\Database\QueryException $e) {
            $this->error(__('status.update_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            $this->error(trans('status.update_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
