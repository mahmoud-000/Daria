<?php

namespace Modules\Stage\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Modules\Stage\Models\Stage;
use Modules\Stage\Http\Requests\UpdateStageRequest;

class StageUpdate extends Controller
{
    public function __invoke(UpdateStageRequest $request, Stage $stage)
    {
        try {
            $stage->update($request->validated());

            return $this->success(__('status.updated', ['name' => $stage['name'], 'module' => __('modules.stage')]));
        } catch (\Illuminate\Database\QueryException $e) {
            $this->error(__('status.update_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            $this->error(trans('status.update_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
