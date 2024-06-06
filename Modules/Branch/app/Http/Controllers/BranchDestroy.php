<?php

namespace Modules\Branch\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class BranchDestroy extends Controller
{
    public function __invoke($company)
    {
        DB::transaction(function () use ($company) {
            // Find Branch Ids to delete except the default one
            $branchesIds = collect($company->branches)->where('is_main', '!=', true)->pluck('id')->toArray();
            
            $company->branches()->whereIn('id', $branchesIds)->delete();
        });
    }
}
