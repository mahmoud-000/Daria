<?php

namespace Modules\Pipeline\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Modules\Pipeline\Models\Pipeline;
use Modules\Pipeline\Http\Requests\UpdatePipelineRequest;
use Modules\Stage\Http\Controllers\StageDestroy;
use Modules\Stage\Http\Controllers\StageUpdate;

class PipelineUpdate extends Controller
{
    public function __invoke(UpdatePipelineRequest $request, Pipeline $pipeline)
    {
        try {
            $request = $request->validated();

            $pipeline = DB::transaction(function () use ($pipeline, $request) {
                $pipeline->update(Arr::except($request, ['stages']));

                $stages = $request['stages'];

                // ================================================
                // Check if pipeline has just default stage
                // Then Delete the other stages with stock
                if (count($stages) === 2) {
                    (new StageDestroy)($pipeline);
                }
                // ================================================
                // Check if pipeline has stages to update them
                // If Count 1 stage this is the default one and will be updated
                // If Count greater than 1 stage this is the default one plus other stages and will be updated
                if (count($stages)) {
                    (new StageUpdate)($pipeline, $stages);
                }
                return $pipeline;
            });
            return $this->success(__('status.updated', ['name' => $pipeline['name'], 'module' => __('modules.pipeline')]));
        } catch (\Illuminate\Database\QueryException $e) {
            $this->error(__('status.update_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            $this->error(trans('status.update_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
