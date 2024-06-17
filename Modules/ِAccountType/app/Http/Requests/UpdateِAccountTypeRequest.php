<?php

namespace Modules\ِAccountType\Http\Requests;

use App\Traits\ValidationErrorResponseTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class UpdateِAccountTypeRequest extends FormRequest
{
    use ValidationErrorResponseTrait;
    public function rules()
    {
        return [
            'name'         => ['required', 'string', 'min:3', 'max:100', Rule::unique('ِaccountTypes', 'name')->withoutTrashed()->ignore($this->ِaccountType)],
            'email'     => ['sometimes', 'nullable', 'email', Rule::unique('ِaccountTypes', 'email')->withoutTrashed()->ignore($this->ِaccountType)],
            'phone' => ['sometimes', 'nullable', 'string'],
            'mobile' => ['sometimes', 'nullable', 'string'],
            'country' => ['string', 'nullable'],
            'city' => ['string', 'nullable'],
            'state' => ['string', 'nullable'],
            'zip' => ['string', 'nullable'],
            'first_address' => ['string', 'nullable'],
            'second_address' => ['string', 'nullable'],
            'is_active'    => ['required', 'boolean'],
            'remarks'      => ['string', 'nullable', 'max:255']
        ];
    }

    public function authorize()
    {
        return auth()->user()->is_owner || Gate::allows('edit-ِaccountType');
    }
}
