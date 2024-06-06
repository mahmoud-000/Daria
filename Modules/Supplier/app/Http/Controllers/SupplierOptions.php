<?php

namespace Modules\Supplier\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Supplier\Transformers\SupplierResource;
use Modules\Supplier\Models\Supplier;

class SupplierOptions extends Controller
{
    public function __invoke(Request $req)
    {
        return SupplierResource::collection(
            Supplier::query()
            ->with('media')
            ->where('is_active', true)
            ->when(!empty($req->search), fn ($query) => $query->where('fullname', 'LIKE', '%' . $req->search . '%'))
            ->paginate(10)
        );
    }
}
