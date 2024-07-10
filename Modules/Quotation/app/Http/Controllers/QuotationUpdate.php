<?php

namespace Modules\Quotation\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Arr;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use Modules\Quotation\Models\Quotation;
use Modules\Quotation\Http\Requests\UpdateQuotationRequest;
use Modules\Quotation\Http\Services\QuotationService;
use Modules\Upload\Http\Controllers\FilesAssign;

class QuotationUpdate extends Controller
{
    public function __invoke(UpdateQuotationRequest $request, Quotation $quotation, QuotationService $service)
    {
        try {
            $request = $request->validated();

            if ($service->isDuplicateDetails($request['details'])) return $this->error(__('status.details_dublicate_error'), Response::HTTP_INTERNAL_SERVER_ERROR);

            $quotation = DB::transaction(function () use ($quotation, $request, $service) {
                $quotation = $service->updateInvoice($quotation, Arr::except($request, ['details', 'quotation_documents', 'deletedDetails']));

                if (isset($request['quotation_documents']) && !is_null($request['quotation_documents']) && !array_key_exists('fake', $request['quotation_documents'])) {
                    (new FilesAssign)($request['quotation_documents'], $quotation, 'quotations', 'quotation_documents', true);
                }

                $detailsIsset = isset($request['details']) ? $request['details'] : [];
                $deletedDetailsIsset = isset($request['deletedDetails']) ? $request['deletedDetails'] : [];

                // Update Old Details
                $service->updateDetails($quotation, $detailsIsset);

                // Delete Old Details If in Deleted Details
                $service->destroyDetails($quotation, $deletedDetailsIsset, false);

                $service->createNewDetailsAndUpdateStockInUpdate($detailsIsset, $quotation, Arr::only($request, ['pipeline_id', 'stage_id']));

                return $quotation;
            });
            return $this->success(__('status.updated', ['name' => sprintf('%07d', $quotation['id']), 'module' => __('modules.quotation')]));
        } catch (\Illuminate\Database\QueryException $e) {
            $this->error(__('status.update_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            $this->error(trans('status.update_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
