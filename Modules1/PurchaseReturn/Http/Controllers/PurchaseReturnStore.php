<?php

namespace Modules\PurchaseReturn\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Modules\PurchaseReturn\Entities\PurchaseReturn;
use Modules\PurchaseReturn\Http\Requests\StorePurchaseReturnRequest;
use Modules\PurchaseReturn\Http\Services\PurchaseReturnService;

class PurchaseReturnStore extends Controller
{
    public function __invoke(StorePurchaseReturnRequest $request, PurchaseReturnService $service)
    {
        try {
            $request = $request->validated();
            $purchase_return = DB::transaction(function () use ($request, $service) {
                $isComplete = $service->isComplete($request['pipeline_id'], $request['stage_id']);

                $purchase_return = PurchaseReturn::create(Arr::except($request, ['details', 'payments']) + ['effected' => $isComplete]);

                $service->createDetails($purchase_return, $request->details);
                $service->updateStockInCreate($purchase_return, $request->details, $isComplete);
                $service->createPayments($purchase_return, $request['payments']);

                return $purchase_return;
            });
            return $this->success(__('status.created', ['name' => $purchase_return['name'], 'module' => __('modules.purchase_return')]));
        } catch (\Illuminate\Database\QueryException $e) {
            $this->error(__('status.create_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            $this->error(trans('status.create_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
