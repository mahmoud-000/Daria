<?php

namespace Modules\Quotation\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use Modules\Quotation\Entities\Quotation;
use Modules\Quotation\Http\Requests\UpdateQuotationRequest;
use Modules\Quotation\Http\Services\QuotationService;

class QuotationUpdate extends Controller
{
    public function __invoke(UpdateQuotationRequest $request, Quotation $quotation, QuotationService $service)
    {
        try {
            $request = $request->validated();
            $quotation = DB::transaction(function () use ($quotation, $request, $service) {
                $quotation = $service->updateInvoice($quotation, $request->except(['details', 'deletedDetails']));
                $service->updateDetails($quotation, $request->details);
                $service->destroyDetails($quotation, $request->deletedDetails);
                $service->createNewDetailsAndUpdateStockInUpdate($request->details, $quotation, $request->only(['pipeline_id', 'stage_id']));

                return $quotation;
            });
            return $this->success(__('status.updated', ['name' => $quotation['name'], 'module' => __('modules.quotation')]));
        } catch (\Illuminate\Database\QueryException $e) {
            $this->error(__('status.update_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            $this->error(trans('status.update_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
