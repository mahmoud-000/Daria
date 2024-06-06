<?php

namespace Modules\Company\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Modules\Company\Models\Company;
use Modules\Company\Http\Requests\UpdateCompanyRequest;
use Modules\Branch\Http\Controllers\BranchDestroy;
use Modules\Branch\Http\Controllers\BranchUpdate;
use Modules\Upload\Http\Controllers\FilesAssign;

class CompanyUpdate extends Controller
{
    public function __invoke(UpdateCompanyRequest $request, Company $company)
    {
        try {
            $request = $request->validated();

            $company = DB::transaction(function () use ($company, $request) {
                $company->update(Arr::except($request, ['logo', 'branches']));

                $branches = $request['branches'];

                // ================================================
                // Check if company has just default branche
                // Then Delete the other branches with stock
                if (count($branches) === 1) {
                    (new BranchDestroy)($company);
                }
                // ================================================
                // Check if company has branches to update them
                // If Count 1 branche this is the default one and will be updated
                // If Count greater than 1 branche this is the default one plus other branches and will be updated
                if (count($branches)) {
                    (new BranchUpdate)($company, $branches);
                }

                if (isset($request['logo']) && !is_null($request['logo']) && !array_key_exists('fake', $request['logo'])) {
                    (new FilesAssign)($request['logo'], $company, 'companies', 'logo');
                }
                return $company;
            });
            return $this->success(__('status.updated', ['name' => $company['name'], 'module' => __('modules.company')]));
        } catch (\Illuminate\Database\QueryException $e) {
            $this->error(__('status.update_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            $this->error(trans('status.update_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
