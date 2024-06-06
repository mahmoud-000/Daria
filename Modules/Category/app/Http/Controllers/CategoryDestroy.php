<?php

namespace Modules\Category\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
use Modules\Category\Models\Category;

class CategoryDestroy extends Controller
{
    public function __invoke(Category $category)
    {
        if (!auth()->user()->is_owner)  abort_if(Gate::denies('delete-category'), Response::HTTP_FORBIDDEN, __('permission::messages.gate_denies'));
        try {
            $category->delete();
            return $this->success(__('status.deleted', ['name' => $category->name, 'module' => __('modules.category')]));
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->error(__('status.delete_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            return $this->error(__('status.delete_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
