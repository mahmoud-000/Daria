<?php

namespace Modules\Quotation\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Modules\Quotation\Entities\Quotation;
use Modules\Quotation\Transformers\QuotationResource;

class QuotationsList extends Controller
{
    public function __invoke(Request $req)
    {
        $dir = $req->descending === 'true' ? 'desc' : 'asc';
        return QuotationResource::collection(Quotation::with(['media', 'category:id,name', 'brand:id,name', 'quotationUnit:id,short_name', 'saleUnit:id,short_name'])->search($req->filter)
            ->orderBy($req->sortBy, $dir)
            ->paginate($req->rowsPerPage));
    }
}
