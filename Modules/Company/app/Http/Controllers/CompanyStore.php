<?php

namespace Modules\Company\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Upload\Http\Controllers\FilesAssign;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Modules\Company\Models\Company;
use Modules\Company\Http\Requests\StoreCompanyRequest;
use Modules\Branch\Http\Controllers\BranchStore;

class CompanyStore extends Controller
{
    public function __invoke(StoreCompanyRequest $request)
    {
        try {
            $request = $request->validated();
            $company = DB::transaction(function () use ($request) {
                $branches = $request['branches'];
                $company = Company::create(Arr::except($request, ['logo', 'branches']));
                if (isset($branches) && count($branches)) {
                    (new BranchStore)($company, $branches);
                }

                if (isset($request['logo']) && !is_null($request['logo']) && !array_key_exists('fake', $request['logo'])) {
                    (new FilesAssign)($request['logo'], $company, 'companies', 'logo');
                }
                return $company;
            });
            return $this->success(__('status.created', ['name' => $company['name'], 'module' => __('modules.company')]));
        } catch (\Illuminate\Database\QueryException $e) {
            $this->error(__('status.create_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            $this->error(trans('status.create_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
