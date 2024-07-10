<?php

namespace Modules\Sale\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Arr;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use Modules\Sale\Models\Sale;
use Modules\Sale\Http\Requests\UpdateSaleRequest;
use Modules\Sale\Http\Services\SaleService;
use Modules\Upload\Http\Controllers\FilesAssign;

class SaleUpdate extends Controller
{
    public function __invoke(UpdateSaleRequest $request, Sale $sale, SaleService $service)
    {
        try {
            $request = $request->validated();

            if ($service->isDuplicateDetails($request['details'])) return $this->error(__('status.details_dublicate_error'), Response::HTTP_INTERNAL_SERVER_ERROR);

            $old_isComplete = $service->isComplete($sale->pipeline_id, $sale->stage_id);
            $old_invoice_effected = $sale->effected;
            $sale = DB::transaction(function () use ($sale, $request, $service, $old_isComplete, $old_invoice_effected) {
                $sale = $service->updateInvoice($sale, Arr::except($request, ['details', 'payments', 'sale_documents', 'deletedDetails', 'deletedPayments']));

                if (isset($request['sale_documents']) && !is_null($request['sale_documents']) && !array_key_exists('fake', $request['sale_documents'])) {
                    (new FilesAssign)($request['sale_documents'], $sale, 'sales', 'sale_documents', true);
                }

                $detailsIsset = isset($request['details']) ? $request['details'] : [];
                $deletedDetailsIsset = isset($request['deletedDetails']) ? $request['deletedDetails'] : [];

                $paymentsIdIsset = isset($request['payments']) ? $request['payments'] : [];
                $deletedPaymentsIdIsset = isset($request['deletedPayments']) ? $request['deletedPayments'] : [];

                // Update Old Details
                $service->updateDetails($sale, $detailsIsset);

                // Update Old Details Stock
                $service->updateStockForOldDetails($sale, $detailsIsset, Arr::only($request, ['pipeline_id', 'stage_id']), $old_isComplete, $old_invoice_effected);

                // Delete Old Details If in Deleted Details
                $service->destroyDetails($sale, $deletedDetailsIsset, $old_isComplete);

                $service->destroyPayments($sale, $deletedPaymentsIdIsset);

                $service->createNewDetailsAndUpdateStockInUpdate($detailsIsset, $sale, Arr::only($request, ['pipeline_id', 'stage_id']));
                $service->createPayments($sale, $paymentsIdIsset);

                return $sale;
            });
            return $this->success(__('status.updated', ['name' => sprintf('%07d', $sale['id']), 'module' => __('modules.sale')]));
        } catch (\Illuminate\Database\QueryException $e) {
            $this->error(__('status.update_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            $this->error(trans('status.update_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
