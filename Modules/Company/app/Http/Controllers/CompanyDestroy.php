<?php

namespace Modules\Company\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
use Modules\Company\Models\Company;

class CompanyDestroy extends Controller
{
    public function __invoke(Company $company)
    {
        if (!auth()->user()->is_owner)  abort_if(Gate::denies('delete-company'), Response::HTTP_FORBIDDEN, __('permission::messages.gate_denies'));
        try {
            $company->delete();
            return $this->success(__('status.deleted', ['name' => $company->name, 'module' => __('modules.company')]));
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->error(__('status.delete_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            return $this->error(__('status.delete_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
