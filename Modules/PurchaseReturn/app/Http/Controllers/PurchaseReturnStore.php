<?php

namespace Modules\PurchaseReturn\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Modules\PurchaseReturn\Models\PurchaseReturn;
use Modules\PurchaseReturn\Http\Requests\StorePurchaseReturnRequest;
use Modules\PurchaseReturn\Http\Services\PurchaseReturnService;
use Modules\Upload\Http\Controllers\FilesAssign;

class PurchaseReturnStore extends Controller
{
    public function __invoke(StorePurchaseReturnRequest $request, PurchaseReturnService $service)
    {
        try {
            $request = $request->validated();

            if ($service->isDuplicateDetails($request['details'])) return $this->error(__('status.details_dublicate_error'), Response::HTTP_INTERNAL_SERVER_ERROR);

            $purchaseReturn = DB::transaction(function () use ($request, $service) {
                $isComplete = $service->isComplete($request['pipeline_id'], $request['stage_id']);

                $purchaseReturn = PurchaseReturn::create(Arr::except($request, ['details', 'payments', 'purchaseReturn_documents']) + ['effected' => $isComplete]);

                if (isset($request['purchaseReturn_documents']) && !is_null($request['purchaseReturn_documents']) && !array_key_exists('fake', $request['purchaseReturn_documents'])) {
                    (new FilesAssign)($request['purchaseReturn_documents'], $purchaseReturn, 'purchaseReturns', 'purchaseReturn_documents', true);
                }

                $detailsIsset = isset($request['details']) ? $request['details'] : [];
                $paymentsIsset = isset($request['payments']) ? $request['payments'] : [];

                $createdDetails = $service->createDetails($purchaseReturn, $detailsIsset);
                $service->updateStockInCreate($purchaseReturn, $createdDetails, $isComplete);
                $service->createPayments($purchaseReturn, $paymentsIsset);

                return $purchaseReturn;
            });
            return $this->success(__('status.created', ['name' => sprintf('%07d', $purchaseReturn['id']), 'module' => __('modules.purchaseReturn')]));
        } catch (\Illuminate\Database\QueryException $e) {
            $this->error(__('status.create_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            $this->error(trans('status.create_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
