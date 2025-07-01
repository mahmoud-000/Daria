<?php

namespace Modules\Category\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Modules\Category\Models\Category;
use Modules\Category\Transformers\CategoryResource;
use Symfony\Component\HttpFoundation\Response;

class CategoriesList extends Controller
{
    public function __invoke(Request $req)
    {
        if (!auth()->user()->is_owner)  abort_if(!Gate::any(['list-category', auth()->user()->is_owner]), Response::HTTP_FORBIDDEN, __('permission::messages.gate_denies'));
        $dir = $req->descending === 'true' ? 'desc' : 'asc';
        return CategoryResource::collection(
            Category::query()
                ->with(['parent', 'parent.media', 'media'])->search($req->filter)
                ->orderBy($req->sortBy, $dir)
                ->paginate($req->rowsPerPage)
        );
    }
}
