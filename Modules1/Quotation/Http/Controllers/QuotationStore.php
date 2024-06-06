<?php

namespace Modules\Quotation\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Modules\Quotation\Entities\Quotation;
use Modules\Quotation\Http\Requests\StoreQuotationRequest;
use Modules\Quotation\Http\Services\QuotationService;

class QuotationStore extends Controller
{
    public function __invoke(StoreQuotationRequest $request, QuotationService $service)
    {
        try {
            $request = $request->validated();
            $quotation = DB::transaction(function () use ($request, $service) {
                $isComplete = $service->isComplete($request['pipeline_id'], $request['stage_id']);

                $quotation = Quotation::create(Arr::except($request, ['details']) + ['effected' => $isComplete]);
                $service->createDetails($quotation, $request->details);

                return $quotation;
            });
            return $this->success(__('status.created', ['name' => $quotation['name'], 'module' => __('modules.quotation')]));
        } catch (\Illuminate\Database\QueryException $e) {
            $this->error(__('status.create_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            $this->error(trans('status.create_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
