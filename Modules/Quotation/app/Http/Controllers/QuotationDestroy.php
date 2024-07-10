<?php

namespace Modules\Quotation\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
use Modules\Quotation\Models\Quotation;

class QuotationDestroy extends Controller
{
    public function __invoke(Quotation $quotation)
    {
        if (!auth()->user()->is_owner)  abort_if(Gate::denies('delete-quotation'), Response::HTTP_FORBIDDEN, __('permission::messages.gate_denies'));
        try {
            $quotation->delete();
            return $this->success(__('status.deleted', ['name' => sprintf('%07d', $quotation['id']), 'module' => __('modules.quotation')]));
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->error(__('status.delete_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            return $this->error(__('status.delete_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
