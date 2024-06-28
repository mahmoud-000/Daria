<?php

namespace Modules\Sale\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Modules\Sale\Models\Sale;
use Modules\Sale\Http\Requests\StoreSaleRequest;
use Modules\Sale\Http\Services\SaleService;
use Modules\Upload\Http\Controllers\FilesAssign;

class SaleStore extends Controller
{
    public function __invoke(StoreSaleRequest $request, SaleService $service)
    {
        try {
            $request = $request->validated();

            if ($service->isDuplicateDetails($request['details'])) return $this->error(__('status.details_dublicate_error'), Response::HTTP_INTERNAL_SERVER_ERROR);

            $sale = DB::transaction(function () use ($request, $service) {
                $isComplete = $service->isComplete($request['pipeline_id'], $request['stage_id']);

                $sale = Sale::create(Arr::except($request, ['details', 'payments', 'sale_documents']) + ['effected' => $isComplete]);

                if (isset($request['sale_documents']) && !is_null($request['sale_documents']) && !array_key_exists('fake', $request['sale_documents'])) {
                    (new FilesAssign)($request['sale_documents'], $sale, 'sales', 'sale_documents', true);
                }

                $detailsIdIsset = isset($request['details']) ? $request['details'] : [];

                $createdDetails = $service->createDetails($sale, $detailsIdIsset);
                $service->updateStockInCreate($sale, $createdDetails, $isComplete);
                $service->createPayments($sale, isset($request['payments']) ? $request['payments'] : []);

                return $sale;
            });
            return $this->success(__('status.created', ['name' => sprintf('%07d', $sale['id']), 'module' => __('modules.sale')]));
        } catch (\Illuminate\Database\QueryException $e) {
            $this->error(__('status.create_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            $this->error(trans('status.create_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
