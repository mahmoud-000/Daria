<?php

namespace Modules\Region\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Modules\Region\Models\Region;
use Modules\Region\Http\Requests\StoreRegionRequest;
use Modules\Upload\Http\Controllers\FilesAssign;

class RegionStore extends Controller
{
    public function __invoke(StoreRegionRequest $request)
    {
        try {
            $request = $request->validated();
            $region = DB::transaction(function () use ($request) {
                $region = Region::create(Arr::except($request, ['logo']));

                if (isset($request['logo']) && !is_null($request['logo']) && !array_key_exists('fake', $request['logo'])) {
                    (new FilesAssign)($request['logo'], $region, 'regions', 'logo');
                }
                return $region;
            });
            return $this->success(__('status.created', ['name' => $region['name'], 'module' => __('modules.region')]));
        } catch (\Illuminate\Database\QueryException $e) {
            $this->error(__('status.create_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            $this->error(trans('status.create_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
