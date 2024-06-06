<?php

namespace Modules\Pipeline\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Pipeline\Transformers\PipelineResource;
use Modules\Pipeline\Models\Pipeline;

class PipelineOptions extends Controller
{
    public function __invoke($moduleName)
    {
        return PipelineResource::collection(Pipeline::with('stages')
            ->where('is_active', true)
            ->where('module_name', $moduleName)
            ->get());
    }
}
