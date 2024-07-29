<?php

namespace Modules\Transfer\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Arr;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use Modules\Transfer\Models\Transfer;
use Modules\Transfer\Http\Requests\UpdateTransferRequest;
use Modules\Transfer\Http\Services\TransferService;
use Modules\Upload\Http\Controllers\FilesAssign;

class TransferUpdate extends Controller
{
    public function __invoke(UpdateTransferRequest $request, Transfer $transfer, TransferService $service)
    {
        try {
            $request = $request->validated();

            if ($service->isDuplicateDetails($request['details'])) return $this->error(__('status.details_dublicate_error'), Response::HTTP_INTERNAL_SERVER_ERROR);

            $transfer = DB::transaction(function () use ($transfer, $request, $service) {
                $transfer = $service->updateInvoice($transfer, Arr::except($request, ['details', 'transfer_documents', 'deletedDetails']));

                if (isset($request['transfer_documents']) && !is_null($request['transfer_documents']) && !array_key_exists('fake', $request['transfer_documents'])) {
                    (new FilesAssign)($request['transfer_documents'], $transfer, 'transfers', 'transfer_documents', true);
                }

                $detailsIsset = isset($request['details']) ? $request['details'] : [];
                $deletedDetailsIsset = isset($request['deletedDetails']) ? $request['deletedDetails'] : [];

                // Update Old Details
                $service->updateDetails($transfer, $detailsIsset);

                // Update Old Details Stock
                $service->updateStockForOldDetails($transfer, $detailsIsset, $request['stage_id']);

                // Delete Old Details If in Deleted Details
                $service->destroyDetails($transfer, $deletedDetailsIsset);

                $service->createNewDetailsAndUpdateStockInUpdate($detailsIsset, $transfer, $request['stage_id']);

                return $transfer;
            });
            return $this->success(__('status.updated', ['name' => sprintf('%07d', $transfer['id']), 'module' => __('modules.transfer')]));
        } catch (\Illuminate\Database\QueryException $e) {
            $this->error(__('status.update_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            $this->error(trans('status.update_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
