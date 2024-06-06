<?php

namespace Modules\Variant\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Modules\Variant\Models\Variant;
use Modules\Variant\Http\Requests\UpdateVariantRequest;

class VariantUpdate extends Controller
{
    public function __invoke(UpdateVariantRequest $request, Variant $variant)
    {
        try {
            $variant->update($request->validated());

            return $this->success(__('status.updated', ['name' => $variant['name'], 'module' => __('modules.variant')]));
        } catch (\Illuminate\Database\QueryException $e) {
            $this->error(__('status.update_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            $this->error(trans('status.update_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
