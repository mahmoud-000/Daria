<?php

namespace Modules\Category\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Category\Transformers\CategoryResource;
use Modules\Category\Models\Category;

class CategoryOptions extends Controller
{
    public function __invoke(Request $req)
    {
        return CategoryResource::collection(
            Category::query()
                ->with('media')
                ->where('is_active', true)
                ->when(
                    !empty($req->form_id),
                    fn ($query) => $query
                        ->where('id', '!=', $req->form_id)
                        ->whereNull('category_id')

                )
                ->when(!empty($req->search), fn ($query) => $query->where('name', 'LIKE', '%' . $req->search . '%'))
                ->paginate(10)
        );
    }
}
