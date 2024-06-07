<?php

namespace Modules\Pipeline\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Pipeline\Transformers\PipelineResource;
use Modules\Pipeline\Models\Pipeline;

class PipelineOptions extends Controller
{
    public function __invoke(Request $req)
    {
        return PipelineResource::collection(
            Pipeline::query()
                ->with('stages')
                ->where('is_active', true)
                ->when($req->app_name, fn ($query) => $query->where('app_name', $req->app_name))
                ->get()
            );
    }
}
