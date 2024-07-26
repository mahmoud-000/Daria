<?php

namespace Modules\SaleReturn\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Modules\SaleReturn\Models\SaleReturn;
use Modules\SaleReturn\Http\Requests\StoreSaleReturnRequest;
use Modules\SaleReturn\Http\Services\SaleReturnService;
use Modules\Upload\Http\Controllers\FilesAssign;

class SaleReturnStore extends Controller
{
    public function __invoke(StoreSaleReturnRequest $request, SaleReturnService $service)
    {
        try {
            $request = $request->validated();

            if ($service->isDuplicateDetails($request['details'])) return $this->error(__('status.details_dublicate_error'), Response::HTTP_INTERNAL_SERVER_ERROR);

            $saleReturn = DB::transaction(function () use ($request, $service) {
                $isComplete = $service::isComplete($request['stage_id']);

                $saleReturn = SaleReturn::create(Arr::except($request, ['details', 'payments', 'saleReturn_documents']) + ['effected' => $isComplete]);

                if (isset($request['saleReturn_documents']) && !is_null($request['saleReturn_documents']) && !array_key_exists('fake', $request['saleReturn_documents'])) {
                    (new FilesAssign)($request['saleReturn_documents'], $saleReturn, 'saleReturns', 'saleReturn_documents', true);
                }

                $detailsIsset = isset($request['details']) ? $request['details'] : [];
                $paymentsIsset = isset($request['payments']) ? $request['payments'] : [];

                $createdDetails = $service->createDetails($saleReturn, $detailsIsset);
                $service->updateStockForNewDetails($saleReturn, $createdDetails, $isComplete);
                $service->createPayments($saleReturn, $paymentsIsset);

                return $saleReturn;
            });
            return $this->success(__('status.created', ['name' => sprintf('%07d', $saleReturn['id']), 'module' => __('modules.saleReturn')]));
        } catch (\Illuminate\Database\QueryException $e) {
            $this->error(__('status.create_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            $this->error(trans('status.create_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
