<?php

namespace Modules\Adjustment\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Modules\Adjustment\Models\Adjustment;
use Modules\Adjustment\Http\Requests\StoreAdjustmentRequest;
use Modules\Adjustment\Http\Services\AdjustmentService;
use Modules\Upload\Http\Controllers\FilesAssign;

class AdjustmentStore extends Controller
{
    public function __invoke(StoreAdjustmentRequest $request, AdjustmentService $service)
    {
        try {
            $request = $request->validated();

            if ($service->isDuplicateDetails($request['details'])) return $this->error(__('status.details_dublicate_error'), Response::HTTP_INTERNAL_SERVER_ERROR);

            $adjustment = DB::transaction(function () use ($request, $service) {
                $isComplete = $service->isComplete($request['pipeline_id'], $request['stage_id']);
                $detailsIdIsset = isset($request['details']) ? $request['details'] : [];

                $adjustment = Adjustment::create(Arr::except($request, ['details', 'adjustment_documents']) + ['effected' => $isComplete, 'items' => count($detailsIdIsset)]);

                if (isset($request['adjustment_documents']) && !is_null($request['adjustment_documents']) && !array_key_exists('fake', $request['adjustment_documents'])) {
                    (new FilesAssign)($request['adjustment_documents'], $adjustment, 'adjustments', 'adjustment_documents', $isComplete);
                }

                $createdDetails = $service->createDetails($adjustment, $detailsIdIsset);
                $service->updateStockInCreate($adjustment, $createdDetails, $isComplete);

                return $adjustment;
            });
            return $this->success(__('status.created', ['name' => sprintf('%07d', $adjustment['id']), 'module' => __('modules.adjustment')]));
        } catch (\Illuminate\Database\QueryException $e) {
            $this->error(__('status.create_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            $this->error(trans('status.create_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
