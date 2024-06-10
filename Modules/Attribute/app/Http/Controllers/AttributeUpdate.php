<?php

namespace Modules\Attribute\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use Modules\Attribute\Models\Attribute;
use Modules\Attribute\Http\Requests\UpdateAttributeRequest;

class AttributeUpdate extends Controller
{
    public function __invoke(UpdateAttributeRequest $request, Attribute $attribute)
    {
        try {
            $attribute = DB::transaction(function () use ($attribute, $request) {
                $attribute->update($request->validated());
                
                return $attribute;
            });
            return $this->success(__('status.updated', ['name' => $attribute['name'], 'module' => __('modules.attribute')]));
        } catch (\Illuminate\Database\QueryException $e) {
            $this->error(__('status.update_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            $this->error(trans('status.update_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
