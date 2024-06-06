<?php

namespace Modules\Branch\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Branch\Models\Branch;

class BranchUpdate extends Controller
{
    public function __invoke($company, $branches)
    {
        // dd($branches);
        DB::transaction(function () use ($company, $branches) {
            $existingBranchesIds = [];
            $existingBranches = [];
            $newBranchesArray = [];
           
            foreach ($branches as $branch) {
                // Check if the branch has id or not
                // If not, create a new branch
                // If has id, update the existing branch
                if (isset($branch['id'])) {
                    $branch['company_id'] = $company->id;
                    $existingBranches[] = $branch;
                    $existingBranchesIds[] = $branch['id'];
                } else {
                    if (!$branch['is_main']) {
                        $newBranchesArray[] = $branch;
                    }
                }
            }
            
            // If has branches to update
            if (count($existingBranchesIds)) {
                // Get the ids of the not existing branches
                $branchesDeletedIds = Branch::whereCompanyId($company->id)
                    ->whereNotIn('id', $existingBranchesIds)
                    ->where('is_main', '!=', true)
                    ->pluck('id')
                    ->toArray();
                
                // Update the existing branches
                Branch::upsert($existingBranches, ['id']);
                // Delete the not existing branches
                Branch::whereIn('id', $branchesDeletedIds)
                    ->delete();
            }
            // Create new branches if not empty
            $newBranchesCreated = [];
            
            if (count($newBranchesArray)) {
                $newBranchesCreated = (new BranchStore)($company, $newBranchesArray);
            }
        });
    }
}
