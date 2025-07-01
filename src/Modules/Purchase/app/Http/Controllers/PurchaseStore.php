<?php

namespace Modules\Purchase\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Modules\Purchase\Models\Purchase;
use Modules\Purchase\Http\Requests\StorePurchaseRequest;
use Modules\Purchase\Http\Services\PurchaseService;
use Modules\Upload\Http\Controllers\FilesAssign;

class PurchaseStore extends Controller
{
    public function __invoke(StorePurchaseRequest $request, PurchaseService $service)
    {
        try {
            $request = $request->validated();

            if ($service->isDuplicateDetails($request['details'])) return $this->error(__('status.details_dublicate_error'), Response::HTTP_INTERNAL_SERVER_ERROR);

            $purchase = DB::transaction(function () use ($request, $service) {
                $isComplete = $service::isComplete($request['stage_id']);

                $purchase = Purchase::create(Arr::except($request, ['details', 'payments', 'purchase_documents']) + ['effected' => $isComplete]);

                if (isset($request['purchase_documents']) && !is_null($request['purchase_documents']) && !array_key_exists('fake', $request['purchase_documents'])) {
                    (new FilesAssign)($request['purchase_documents'], $purchase, 'purchases', 'purchase_documents', true);
                }

                $detailsIsset = isset($request['details']) ? $request['details'] : [];
                $paymentsIsset = isset($request['payments']) ? $request['payments'] : [];

                $createdDetails = $service->createDetails($purchase, $detailsIsset);
                $service->updateStockForNewDetails($purchase, $createdDetails, $isComplete);
                $service->createPayments($purchase, $paymentsIsset);

                return $purchase;
            });
            return $this->success(__('status.created', ['name' => sprintf('%07d', $purchase['id']), 'module' => __('modules.purchase')]));
        } catch (\Illuminate\Database\QueryException $e) {
            $this->error(__('status.create_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            $this->error(trans('status.create_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
