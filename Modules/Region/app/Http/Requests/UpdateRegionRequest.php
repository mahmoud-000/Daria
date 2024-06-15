<?php

namespace Modules\Region\Http\Requests;

use App\Traits\ValidationErrorResponseTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateRegionRequest extends FormRequest
{
    use ValidationErrorResponseTrait;
    public function rules()
    {
        return [
            'name'         => ['required', 'string', 'min:3', 'max:100', Rule::unique('regions', 'name')->withoutTrashed()->ignore($this->region)],
            'is_active'    => ['required', 'boolean'],
            'remarks'      => ['string', 'nullable', 'max:255'],
            'logo'         => ['sometimes', 'array', 'nullable'],
        ];
    }

    public function authorize()
    {
        return auth()->user()->is_owner || Gate::allows('edit-user');
    }
}
