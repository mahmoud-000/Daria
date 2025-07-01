<?php

namespace Modules\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
use Modules\Auth\Transformers\UserProfileResource;
use Modules\User\Models\User;

class UserProfileShow extends Controller
{
    public function __invoke()
    {

        if (!auth()->user()->is_owner)  abort_if(!Gate::any(['show-user-profile', 'edit-user-profile']), Response::HTTP_FORBIDDEN, __('permission::messages.gate_denies'));
        $profile = User::with(['contacts', 'locations', 'media'])
            ->where('id', auth()->id())
            ->first();
        return UserProfileResource::make($profile);
    }
}
