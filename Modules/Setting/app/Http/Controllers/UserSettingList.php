<?php

namespace Modules\Setting\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
use Modules\Setting\Models\Setting;
use Modules\Setting\Transformers\SettingResource;
use Modules\User\Models\User;

class UserSettingList extends Controller
{
    public function __invoke(User $user)
    {
        if (!auth()->user()->is_owner)  abort_if(!Gate::allows('list-user-settings'), Response::HTTP_FORBIDDEN, __('permission::messages.gate_denies'));
        return $this->success(SettingResource::make(Setting::byUser($user->id)->first()));
    }
}
