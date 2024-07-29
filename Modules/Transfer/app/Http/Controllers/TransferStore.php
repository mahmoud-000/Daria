<?php

namespace Modules\Transfer\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Modules\Transfer\Models\Transfer;
use Modules\Transfer\Http\Requests\StoreTransferRequest;
use Modules\Transfer\Http\Services\TransferService;
use Modules\Upload\Http\Controllers\FilesAssign;

class TransferStore extends Controller
{
    public function __invoke(StoreTransferRequest $request, TransferService $service)
    {
        // try {
            $request = $request->validated();

            if ($service->isDuplicateDetails($request['details'])) return $this->error(__('status.details_dublicate_error'), Response::HTTP_INTERNAL_SERVER_ERROR);

            $transfer = DB::transaction(function () use ($request, $service) {
                $isComplete = $service::isComplete($request['stage_id']);

                $transfer = Transfer::create(Arr::except($request, ['details', 'transfer_documents']) + ['effected' => $isComplete]);

                if (isset($request['transfer_documents']) && !is_null($request['transfer_documents']) && !array_key_exists('fake', $request['transfer_documents'])) {
                    (new FilesAssign)($request['transfer_documents'], $transfer, 'transfers', 'transfer_documents', true);
                }

                $detailsIsset = isset($request['details']) ? $request['details'] : [];

                $createdDetails = $service->createDetails($transfer, $detailsIsset);
                $service->updateStockForNewDetails($transfer, $createdDetails, $isComplete);

                return $transfer;
            });
            return $this->success(__('status.created', ['name' => sprintf('%07d', $transfer['id']), 'module' => __('modules.transfer')]));
        // } catch (\Illuminate\Database\QueryException $e) {
        //     $this->error(__('status.create_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        // } catch (\Exception $e) {
        //     $this->error(trans('status.create_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        // }
    }
}
