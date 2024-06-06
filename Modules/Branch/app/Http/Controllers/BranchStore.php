<?php

namespace Modules\Branch\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Modules\Branch\Models\Branch;
use Modules\Branch\Http\Requests\StoreBranchRequest;

class BranchStore extends Controller
{
    public function __invoke(StoreBranchRequest $request)
    {
        try {
            $branch = Branch::create($request->validated());

            return $this->success(__('status.created', ['name' => $branch['name'], 'module' => __('modules.branch')]));
        } catch (\Illuminate\Database\QueryException $e) {
            $this->error(__('status.create_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            $this->error(trans('status.create_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
