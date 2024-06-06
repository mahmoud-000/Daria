<?php

namespace Modules\Branch\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Modules\Branch\Models\Branch;
use Modules\Branch\Http\Requests\UpdateBranchRequest;

class BranchUpdate extends Controller
{
    public function __invoke(UpdateBranchRequest $request, Branch $branch)
    {
        try {
            $branch->update($request->validated());

            return $this->success(__('status.updated', ['name' => $branch['name'], 'module' => __('modules.branch')]));
        } catch (\Illuminate\Database\QueryException $e) {
            $this->error(__('status.update_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            $this->error(trans('status.update_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
