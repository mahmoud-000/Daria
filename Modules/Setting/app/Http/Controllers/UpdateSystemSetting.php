<?php

namespace Modules\Setting\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
use Modules\Setting\Models\Setting;
use Modules\Setting\Http\Requests\SettingCreateRequest;
use Modules\Setting\Transformers\SettingResource;
use Modules\Upload\Http\Controllers\FilesAssign;

class UpdateSystemSetting extends Controller
{
    public function __invoke(SettingCreateRequest $request, Setting $setting)
    {
        if (!auth()->user()->is_owner)  abort_if(Gate::denies('edit-system-settings'), Response::HTTP_FORBIDDEN, __('permission::messages.gate_denies'));
        collect($request->validated())->each(function (array $row) {
            if($row['key'] === 'system_logo') {
                $setting = Setting::updateOrCreate(
                    ['key' => $row['key']],
                    ['value' => '']
                );

                if (isset($row['value']) && !is_null($row['value']) && !array_key_exists('fake', $row['value'])) {
                    (new FilesAssign)($row['value'], $setting, 'settings', 'system_logo');
                }
            }
            if($row['key'] !== 'system_logo') {
                Setting::updateOrCreate(
                    ['key' => $row['key']],
                    ['value' => $row['value']]
                );
            }
        });

        return $this->success(SettingResource::collection(Setting::systemOnly()->get()));
    }
}
