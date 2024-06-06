<?php

namespace Modules\Role\Http\Controllers;

use App\Enums\ActiveEnum;
use App\Http\Controllers\Controller;
use Modules\Role\Models\Role;
use Modules\Role\Transformers\RoleResource;

class RoleOptions extends Controller
{
    public function __invoke()
    {
        return RoleResource::collection(Role::with('permissions:id,name')->where('is_active', ActiveEnum::ACTIVED)->get());   
    }
}
