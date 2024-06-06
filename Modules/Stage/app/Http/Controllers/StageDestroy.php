<?php

namespace Modules\Stage\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class StageDestroy extends Controller
{
    public function __invoke($pipeline)
    {
        DB::transaction(function () use ($pipeline) {
            // Find Stage Ids to delete except the default one
            $stagesIds = collect($pipeline->stages)->where('default', '!=', true)->pluck('id')->toArray();
            
            $pipeline->stages()->whereIn('id', $stagesIds)->delete();
        });
    }
}
