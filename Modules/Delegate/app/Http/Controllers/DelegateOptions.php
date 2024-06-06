<?php

namespace Modules\Delegate\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Delegate\Transformers\DelegateResource;
use Modules\Delegate\Models\Delegate;

class DelegateOptions extends Controller
{
    public function __invoke(Request $req)
    {
        return DelegateResource::collection(
            Delegate::query()
            ->with('media')
            ->where('is_active', true)
            ->when(!empty($req->search), fn ($query) => $query->where('fullname', 'LIKE', '%' . $req->search . '%'))
            ->paginate(10)
        );
    }
}
