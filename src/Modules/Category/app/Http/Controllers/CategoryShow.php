<?php

namespace Modules\Category\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
use Modules\Category\Models\Category;
use Modules\Category\Transformers\CategoryResource;

class CategoryShow extends Controller
{
    public function __invoke(Category $category)
    {
        if (!auth()->user()->is_owner)  abort_if(Gate::denies('show-category'), Response::HTTP_FORBIDDEN, __('permission::messages.gate_denies'));
        return CategoryResource::make($category->load(['parent', 'parent.media', 'media']));
    }
}
