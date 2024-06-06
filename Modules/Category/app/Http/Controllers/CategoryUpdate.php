<?php

namespace Modules\Category\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Modules\Category\Models\Category;
use Modules\Category\Http\Requests\UpdateCategoryRequest;
use Modules\Upload\Http\Controllers\FilesAssign;

class CategoryUpdate extends Controller
{
    public function __invoke(UpdateCategoryRequest $request, Category $category)
    {
        try {
            $request = $request->validated();
            $category = DB::transaction(function () use ($category, $request) {
                $category->update(Arr::except($request, ['logo', 'category']));
                
                if (isset($request['logo']) && !is_null($request['logo']) && !array_key_exists('fake', $request['logo'])) {
                    (new FilesAssign)($request['logo'], $category, 'categories', 'logo');
                }
                return $category;
            });
            return $this->success(__('status.updated', ['name' => $category['name'], 'module' => __('modules.category')]));
        } catch (\Illuminate\Database\QueryException $e) {
            $this->error(__('status.update_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            $this->error(trans('status.update_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
