<?php

namespace Modules\Category\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Modules\Category\Models\Category;
use Modules\Category\Http\Requests\StoreCategoryRequest;
use Modules\Upload\Http\Controllers\FilesAssign;

class CategoryStore extends Controller
{
    public function __invoke(StoreCategoryRequest $request)
    {
        try {
            $request = $request->validated();
            $category = DB::transaction(function () use ($request) {
                $category = Category::create(Arr::except($request, ['logo', 'category']));

                if (isset($request['logo']) && !is_null($request['logo']) && !array_key_exists('fake', $request['logo'])) {
                    (new FilesAssign)($request['logo'], $category, 'categories', 'logo');
                }
                return $category;
            });
            return $this->success(__('status.created', ['name' => $category['name'], 'module' => __('modules.category')]));
        } catch (\Illuminate\Database\QueryException $e) {
            $this->error(__('status.create_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            $this->error(trans('status.create_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
