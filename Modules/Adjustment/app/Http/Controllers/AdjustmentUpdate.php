<?php

namespace Modules\Adjustment\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Arr;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use Modules\Adjustment\Models\Adjustment;
use Modules\Adjustment\Http\Requests\UpdateAdjustmentRequest;
use Modules\Adjustment\Http\Services\AdjustmentService;
use Modules\Upload\Http\Controllers\FilesAssign;

class AdjustmentUpdate extends Controller
{
    public function __invoke(UpdateAdjustmentRequest $request, Adjustment $adjustment, AdjustmentService $service)
    {
        try {
            $request = $request->validated();

            if ($service->isDuplicateDetails($request['details'])) return $this->error(__('status.details_dublicate_error'), Response::HTTP_INTERNAL_SERVER_ERROR);

            $adjustment = DB::transaction(function () use ($adjustment, $request, $service) {
                $detailsIsset = isset($request['details']) ? $request['details'] : [];
                $adjustment = $service->updateInvoice($adjustment, Arr::except($request, ['details', 'adjustment_documents', 'deletedDetails']) + ['items' => count($detailsIsset)]);

                if (isset($request['adjustment_documents']) && !is_null($request['adjustment_documents']) && !array_key_exists('fake', $request['adjustment_documents'])) {
                    (new FilesAssign)($request['adjustment_documents'], $adjustment, 'adjustments', 'adjustment_documents', true);
                }

                $deletedDetailsIsset = isset($request['deletedDetails']) ? $request['deletedDetails'] : [];

                // Update Old Details
                $service->updateDetails($adjustment, $detailsIsset);

                // Update Old Details Stock
                $service->updateStockForOldDetails($adjustment, $detailsIsset, $request['stage_id']);

                // Delete Old Details If in Deleted Details
                $service->destroyDetails($adjustment, $deletedDetailsIsset);

                $service->createNewDetailsAndUpdateStockInUpdate($detailsIsset, $adjustment, $request['stage_id']);

                return $adjustment;
            });
            return $this->success(__('status.updated', ['name' => sprintf('%07d', $adjustment['id']), 'module' => __('modules.adjustment')]));
        } catch (\Illuminate\Database\QueryException $e) {
            $this->error(__('status.update_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            $this->error(trans('status.update_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
