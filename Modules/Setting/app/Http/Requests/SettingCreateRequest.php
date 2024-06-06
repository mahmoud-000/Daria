<?php

namespace Modules\Setting\Http\Requests;

use App\Traits\ValidationErrorResponseTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class SettingCreateRequest extends FormRequest
{
    use ValidationErrorResponseTrait;

    public function rules()
    {
        return [
            '*.key' => ['required'],
            '*.value' => ['nullable'],
        ];
    }

    public function authorize()
    {
        return Gate::any(['edit-system-settings', 'edit-user-settings']);
    }
}
