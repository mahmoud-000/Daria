<?php

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
use Modules\User\Models\User;
use Modules\User\Transformers\UserResource;

class UserShow extends Controller
{
    public function __invoke(User $user)
    {
        if (!auth()->user()->is_owner)  abort_if(!Gate::any(['show-user', 'edit-user']), Response::HTTP_FORBIDDEN, __('permission::messages.gate_denies'));
        return UserResource::make($user->load(['roles:id', 'permissions:id,name', 'contacts', 'locations', 'media']));
    }
}
