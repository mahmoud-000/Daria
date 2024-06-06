<?php

namespace Modules\Role\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
use Modules\Role\Models\Role;
use Modules\Role\Transformers\RoleResource;

class RoleShow extends Controller
{
    public function __invoke(Role $role)
    {
        if (!auth()->user()->is_owner)  abort_if(!Gate::any(['show-role', 'edit-role']), Response::HTTP_FORBIDDEN, __('permission::messages.gate_denies'));
        return RoleResource::make($role->load(['permissions:name']));
    }
}
