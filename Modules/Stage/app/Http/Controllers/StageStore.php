<?php

namespace Modules\Stage\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Modules\Stage\Models\Stage;
use Modules\Stage\Http\Requests\StoreStageRequest;

class StageStore extends Controller
{
    public function __invoke(StoreStageRequest $request)
    {
        try {

            $stage = Stage::create($request->validated());

            return $this->success(__('status.created', ['name' => $stage['name'], 'module' => __('modules.stage')]));
        } catch (\Illuminate\Database\QueryException $e) {
            $this->error(__('status.create_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            $this->error(trans('status.create_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
