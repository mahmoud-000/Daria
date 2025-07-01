<?php

namespace Modules\Variant\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Modules\Variant\Models\Variant;
use Modules\Variant\Http\Requests\StoreVariantRequest;
use Modules\Upload\Http\Controllers\FilesAssign;

class VariantStore extends Controller
{
    public function __invoke(StoreVariantRequest $request)
    {
        try {
            $request = $request->validated();
            $variant = DB::transaction(function () use ($request) {
                $variant = Variant::create(Arr::except($request, ['image']));

                if (isset($request['image']) && !is_null($request['image']) && !array_key_exists('fake', $request['image'])) {
                    (new FilesAssign)($request['image'], $variant, 'variants', 'image');
                }
                return $variant;
            });
            return $this->success(__('status.created', ['name' => $variant['name'], 'module' => __('modules.variant')]));
        } catch (\Illuminate\Database\QueryException $e) {
            $this->error(__('status.create_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            $this->error(trans('status.create_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
