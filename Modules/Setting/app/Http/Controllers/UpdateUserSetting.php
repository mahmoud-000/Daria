<?php

namespace Modules\Setting\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
use Modules\Setting\Models\Setting;
use Modules\Setting\Http\Requests\SettingCreateRequest;
use Modules\Setting\Transformers\SettingResource;

class UpdateUserSetting extends Controller
{
    public function __invoke(SettingCreateRequest $request, Setting $setting)
    {
        if (!auth()->user()->is_owner)  abort_if(Gate::denies('edit-user-settings'), Response::HTTP_FORBIDDEN, __('permission::messages.gate_denies'));
        collect($request->validated())->each(function (array $row) {
            Setting::updateOrCreate(
                ['key' => $row['key']],
                ['value' => $row['value'], 'user_id' => auth()->id()]
            );
        });
        return $this->success(SettingResource::collection(Setting::where('user_id', '!=', null)->get()));
    }
}
