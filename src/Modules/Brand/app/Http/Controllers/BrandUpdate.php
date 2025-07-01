<?php

namespace Modules\Brand\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Modules\Brand\Models\Brand;
use Modules\Brand\Http\Requests\UpdateBrandRequest;
use Modules\Upload\Http\Controllers\FilesAssign;

class BrandUpdate extends Controller
{
    public function __invoke(UpdateBrandRequest $request, Brand $brand)
    {
        try {
            $request = $request->validated();
            $brand = DB::transaction(function () use ($brand, $request) {
                $brand->update(Arr::except($request, ['logo']));
                
                if (isset($request['logo']) && !is_null($request['logo']) && !array_key_exists('fake', $request['logo'])) {
                    (new FilesAssign)($request['logo'], $brand, 'brands', 'logo');
                }
                return $brand;
            });
            return $this->success(__('status.updated', ['name' => $brand['name'], 'module' => __('modules.brand')]));
        } catch (\Illuminate\Database\QueryException $e) {
            $this->error(__('status.update_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            $this->error(trans('status.update_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
