<?php

namespace Modules\Attribute\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
use Modules\Attribute\Models\Attribute;

class AttributeDestroy extends Controller
{
    public function __invoke(Attribute $attribute)
    {
        if (!auth()->user()->is_owner)  abort_if(Gate::denies('delete-attribute'), Response::HTTP_FORBIDDEN, __('permission::messages.gate_denies'));
        try {
            $attribute->delete();
            return $this->success(__('status.deleted', ['name' => $attribute->name, 'module' => __('modules.attribute')]));
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->error(__('status.delete_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            return $this->error(__('status.delete_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
