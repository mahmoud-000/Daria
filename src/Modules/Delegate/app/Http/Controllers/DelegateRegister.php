<?php

namespace Modules\Delegate\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Modules\Upload\Http\Controllers\FilesAssign;
use Modules\Delegate\Models\Delegate;
use Modules\Delegate\Http\Requests\RegisterDelegateRequest;

class DelegateRegister extends Controller
{
    public function __invoke(RegisterDelegateRequest $request)
    {
        try {
            $request = $request->validated();
            $delegate = DB::transaction(function () use ($request) {
                $delegate = Delegate::create(Arr::except($request, ['contacts', 'locations', 'logo']));

                if (isset($request['contacts']) && count($request['contacts'])) {
                    $delegate->contacts()->createMany($request['contacts']);
                }

                if (isset($request['locations']) && count($request['locations'])) {
                    $delegate->locations()->createMany($request['locations']);
                }

                if (isset($request['logo']) && !is_null($request['logo']) && !array_key_exists('fake', $request['logo'])) {
                    (new FilesAssign)($request['logo'], $delegate, 'delegates', 'logo');
                }

                return $delegate;
            });
            return $this->success(__('status.created', ['name' => $delegate['username'], 'module' => __('modules.delegate')]));
        } catch (\Illuminate\Database\QueryException $e) {
            $this->error(__('status.create_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            $this->error(trans('status.create_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
