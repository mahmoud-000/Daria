<?php

namespace Modules\Pipeline\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Modules\Pipeline\Models\Pipeline;
use Modules\Pipeline\Http\Requests\StorePipelineRequest;

class PipelineStore extends Controller
{
    public function __invoke(StorePipelineRequest $request)
    {
        try {
            $request = $request->validated();
            
            $pipeline = DB::transaction(function () use ($request) {
                $stages = $request['stages'];
                $pipeline = Pipeline::create(Arr::except($request, ['stages']));
                if (isset($stages) && count($stages)) {
                    $pipeline->stages()->createMany($stages);
                }
                return $pipeline;
            });
            return $this->success(__('status.created', ['name' => $pipeline['name'], 'module' => __('modules.pipeline')]));
        } catch (\Illuminate\Database\QueryException $e) {
            $this->error(__('status.create_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            $this->error(trans('status.create_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
