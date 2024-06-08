<?php

namespace Modules\Pipeline\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Modules\Pipeline\Models\Pipeline;
use Modules\Pipeline\Http\Requests\UpdatePipelineRequest;
use Modules\Stage\Models\Stage;

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
                // Check if pipeline has just is_default stage
                // Then Delete the other stages with stock
                if (count($stages) === 2) {
                    // Find Stage Ids to delete except the is_default one
                    $stagesIds = collect($pipeline->stages)->where('is_default', '!=', true)->pluck('id')->toArray();

                    $pipeline->stages()->whereIn('id', $stagesIds)->delete();
                }
                // ================================================
                // Check if pipeline has stages to update them
                // If Count 1 stage this is the is_default one and will be updated
                // If Count greater than 1 stage this is the is_default one plus other stages and will be updated
                if (count($stages)) {
                    $this->stagesUpdatedAndDestroy($pipeline, $stages);
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

    protected function stagesUpdatedAndDestroy($pipeline, $stages)
    {
        $existingStagesIds = [];
        $existingStages = [];
        $newStagesArray = [];

        foreach ($stages as $stage) {
            // Check if the stage has id or not
            // If not, create a new stage
            // If has id, update the existing stage
            if (isset($stage['id'])) {
                $stage['pipeline_id'] = $pipeline->id;
                $existingStages[] = $stage;
                $existingStagesIds[] = $stage['id'];
            } else {
                if (!$stage['is_default']) {
                    $newStagesArray[] = $stage;
                }
            }
        }
        // If has stages to update
        if (count($existingStagesIds)) {
            // Get the ids of the not existing stages
            $stagesDeletedIds = Stage::wherePipelineId($pipeline->id)
                ->whereNotIn('id', $existingStagesIds)
                ->where('is_default', '!=', true)
                ->pluck('id')
                ->toArray();
            // Update the existing stages
            Stage::upsert($existingStages, ['id']);
            // Delete the not existing stages
            Stage::whereIn('id', $stagesDeletedIds)
                ->delete();
        }
        // Create new stages if not empty
        if (count($newStagesArray)) {
            $pipeline->stages()->createMany($newStagesArray);
        }
    }
}
