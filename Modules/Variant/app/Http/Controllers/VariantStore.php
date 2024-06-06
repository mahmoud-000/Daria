<?php

namespace Modules\Variant\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Modules\Variant\Models\Variant;
use Modules\Variant\Http\Requests\StoreVariantRequest;

class VariantStore extends Controller
{
    public function __invoke(StoreVariantRequest $request)
    {
        try {

            $variant = Variant::create($request->validated());

            return $this->success(__('status.created', ['name' => $variant['name'], 'module' => __('modules.variant')]));
        } catch (\Illuminate\Database\QueryException $e) {
            $this->error(__('status.create_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            $this->error(trans('status.create_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
