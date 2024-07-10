<?php

namespace Modules\Quotation\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Modules\Quotation\Models\Quotation;
use Modules\Quotation\Http\Requests\StoreQuotationRequest;
use Modules\Quotation\Http\Services\QuotationService;
use Modules\Upload\Http\Controllers\FilesAssign;

class QuotationStore extends Controller
{
    public function __invoke(StoreQuotationRequest $request, QuotationService $service)
    {
        try {
            $request = $request->validated();

            if ($service->isDuplicateDetails($request['details'])) return $this->error(__('status.details_dublicate_error'), Response::HTTP_INTERNAL_SERVER_ERROR);

            $quotation = DB::transaction(function () use ($request, $service) {

                $quotation = Quotation::create(Arr::except($request, ['details', 'quotation_documents']));

                if (isset($request['quotation_documents']) && !is_null($request['quotation_documents']) && !array_key_exists('fake', $request['quotation_documents'])) {
                    (new FilesAssign)($request['quotation_documents'], $quotation, 'quotations', 'quotation_documents', true);
                }

                $detailsIdIsset = isset($request['details']) ? $request['details'] : [];

                $service->createDetails($quotation, $detailsIdIsset);

                return $quotation;
            });
            return $this->success(__('status.created', ['name' => sprintf('%07d', $quotation['id']), 'module' => __('modules.quotation')]));
        } catch (\Illuminate\Database\QueryException $e) {
            $this->error(__('status.create_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            $this->error(trans('status.create_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
