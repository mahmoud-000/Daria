<?php

namespace Modules\Category\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
use Modules\Category\Models\Category;

class CategoryBulkDestroy extends Controller
{
    public function __invoke(Request $request)
    {
        if (!auth()->user()->is_owner)  abort_if(Gate::denies('bulk-delete-category'), Response::HTTP_FORBIDDEN, __('permission::messages.gate_denies'));
        try {
            Category::whereIn('id', $request->ids)->delete();
            // $category->permissions()->detach();
            // $category->roles()->detach();
            // $category->contacts()->delete();
            // $category->locations()->delete();
            // $category->media()->delete();
            return $this->success(__('status.deleted_selected_success'));
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->error(__('status.delete_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            return $this->error(__('status.delete_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
