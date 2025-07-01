<?php

namespace Modules\Item\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
use Modules\Item\Models\Item;

class ItemDestroy extends Controller
{
    public function __invoke(Item $item)
    {
        if (!auth()->user()->is_owner)  abort_if(Gate::denies('delete-item'), Response::HTTP_FORBIDDEN, __('permission::messages.gate_denies'));
        try {
            $item->delete();
            $item->variants()->delete();
            $item->stock()->delete();
            return $this->success(__('status.deleted', ['name' => $item->name, 'module' => __('modules.item')]));
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->error(__('status.delete_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            return $this->error(__('status.delete_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
