<?php

namespace Modules\Quotation\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Modules\Quotation\Models\Quotation;
use Modules\Quotation\Transformers\QuotationsCollectionResource;

class QuotationsList extends Controller
{
    public function __invoke(Request $req)
    {
        $dir = $req->descending === 'true' ? 'desc' : 'asc';
        return QuotationsCollectionResource::collection(
            Quotation::query()
                ->select(['id', 'date', 'warehouse_id', 'customer_id', 'pipeline_id', 'grand_total', 'created_at', 'updated_at'])
                ->with(['warehouse:id,name', 'customer:id,fullname', 'pipeline:id,name'])
                ->search($req->filter)
                ->orderBy($req->sortBy, $dir)
                ->paginate($req->rowsPerPage)
        );
    }
}
