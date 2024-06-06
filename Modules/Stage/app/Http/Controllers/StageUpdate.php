<?php

namespace Modules\Stage\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Stage\Models\Stage;

class StageUpdate extends Controller
{
    public function __invoke($pipeline, $stages)
    {
        DB::transaction(function () use ($pipeline, $stages) {
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
                    if (!$stage['default']) {
                        $newStagesArray[] = $stage;
                    }
                }
            }
            // If has stages to update
            if (count($existingStagesIds)) {
                // Get the ids of the not existing stages
                $stagesDeletedIds = Stage::wherePipelineId($pipeline->id)
                    ->whereNotIn('id', $existingStagesIds)
                    ->where('default', '!=', true)
                    ->pluck('id')
                    ->toArray();
                // Update the existing stages
                Stage::upsert($existingStages, ['id']);
                // Delete the not existing stages
                Stage::whereIn('id', $stagesDeletedIds)
                    ->delete();
            }
            // Create new stages if not empty
            $newStagesCreated = [];
            if (count($newStagesArray)) {
                $newStagesCreated = (new StageStore)($pipeline, $newStagesArray);
            }
        });
    }
}
