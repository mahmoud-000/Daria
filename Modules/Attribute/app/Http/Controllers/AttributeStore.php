<?php

namespace Modules\Attribute\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use Modules\Attribute\Models\Attribute;
use Modules\Attribute\Http\Requests\StoreAttributeRequest;

class AttributeStore extends Controller
{
    public function __invoke(StoreAttributeRequest $request)
    {
        try {
            $attribute = DB::transaction(function () use ($request) {
                $attribute = Attribute::create($request->validated());

                return $attribute;
            });
            return $this->success(__('status.created', ['name' => $attribute['name'], 'module' => __('modules.attribute')]));
        } catch (\Illuminate\Database\QueryException $e) {
            $this->error(__('status.create_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            $this->error(trans('status.create_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
