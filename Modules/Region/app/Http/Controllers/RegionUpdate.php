<?php

namespace Modules\Region\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Modules\Region\Models\Region;
use Modules\Region\Http\Requests\UpdateRegionRequest;
use Modules\Upload\Http\Controllers\FilesAssign;

class RegionUpdate extends Controller
{
    public function __invoke(UpdateRegionRequest $request, Region $region)
    {
        try {
            $request = $request->validated();
            $region = DB::transaction(function () use ($region, $request) {
                $region->update(Arr::except($request, ['logo']));
                
                if (isset($request['logo']) && !is_null($request['logo']) && !array_key_exists('fake', $request['logo'])) {
                    (new FilesAssign)($request['logo'], $region, 'regions', 'logo');
                }
                return $region;
            });
            return $this->success(__('status.updated', ['name' => $region['name'], 'module' => __('modules.region')]));
        } catch (\Illuminate\Database\QueryException $e) {
            $this->error(__('status.update_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            $this->error(trans('status.update_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
