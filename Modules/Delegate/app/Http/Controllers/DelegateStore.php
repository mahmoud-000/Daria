<?php

namespace Modules\Delegate\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Modules\Upload\Http\Controllers\FilesAssign;
use Modules\Delegate\Models\Delegate;
use Modules\Delegate\Http\Requests\StoreDelegateRequest;

class DelegateStore extends Controller
{
    public function __invoke(StoreDelegateRequest $request)
    {
        try {
            $request = $request->validated();
            $delegate = DB::transaction(function () use ($request) {
                $delegate = Delegate::create(Arr::except($request, ['contacts', 'locations', 'avatar']));

                if (isset($request['contacts']) && count($request['contacts'])) {
                    $delegate->contacts()->createMany($request['contacts']);
                }

                if (isset($request['locations']) && count($request['locations'])) {
                    $delegate->locations()->createMany($request['locations']);
                }

                if (isset($request['avatar']) && !is_null($request['avatar']) && !array_key_exists('fake', $request['avatar'])) {
                    (new FilesAssign)($request['avatar'], $delegate, 'delegates', 'avatar');
                }
              
                return $delegate;
            });
            return $this->success(__('status.created', ['name' => $delegate['fullname'], 'module' => __('modules.delegate')]));
        } catch (\Illuminate\Database\QueryException $e) {
            $this->error(__('status.create_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            $this->error(trans('status.create_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
