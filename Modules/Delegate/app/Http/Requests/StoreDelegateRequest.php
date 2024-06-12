<?php

namespace Modules\Delegate\Http\Requests;

use App\Enums\ICTypesEnum;
use App\Traits\ValidationErrorResponseTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class StoreDelegateRequest extends FormRequest
{
    use ValidationErrorResponseTrait;
    public function rules()
    {
        return [
            'fullname'  => ['required', 'string', 'min:8', 'max:100', Rule::unique('delegates', 'fullname')->withoutTrashed()->ignore($this->delegate)],
            'company_name'  => [Rule::requiredIf(!$this->type || $this->type === ICTypesEnum::COMPANY->value), 'nullable', 'string', 'min:8', 'max:100', Rule::unique('delegates', 'company_name')->withoutTrashed()->ignore($this->delegate)],
            'email'     => ['nullable', 'email', Rule::unique('delegates', 'email')->withoutTrashed()->ignore($this->delegate)],
            'remarks'       => ['string', 'nullable'],
            'is_active'      => ['sometimes', 'boolean'],

            'locations'     => ['sometimes', 'array', 'present'],
            'locations.*.name' => ['distinct', 'required', 'string', 'min:3', 'max:100', Rule::unique('locations', 'name')->where('locationable_type', 'Delegate')->whereNull('locationable_id')],
            'locations.*.country' => ['string', 'nullable'],
            'locations.*.city' => ['string', 'nullable'],
            'locations.*.state' => ['string', 'nullable'],
            'locations.*.zip' => ['string', 'nullable'],
            'locations.*.first_address' => ['string', 'nullable'],
            'locations.*.second_address' => ['string', 'nullable'],

            'contacts' => ['sometimes', 'array', 'present'],
            'contacts.*.name' => ['distinct', 'required', 'string', 'min:3', 'max:100', Rule::unique('contacts', 'name')->where('contactable_type', 'Delegate')->whereNull('contactable_id')],
            'contacts.*.email' => ['distinct', 'sometimes', 'nullable', 'email', Rule::unique('contacts', 'email')->where('contactable_type', 'Delegate')],
            'contacts.*.phone' => ['distinct', 'sometimes', 'nullable', 'string', Rule::unique('contacts', 'phone')->where('contactable_type', 'Delegate')],
            'contacts.*.mobile' => ['distinct', 'sometimes', 'nullable', 'string', Rule::unique('contacts', 'mobile')->where('contactable_type', 'Delegate')],

            'avatar' => ['sometimes', 'array', 'nullable'],

            'type'          => ['required', 'integer'],
            'commission_type'          => ['required', 'integer'],
            'commission'          => ['required', 'numeric', 'min:0'],
        ];
    }

    public function authorize()
    {
        return auth()->user()->is_owner || Gate::allows('create-delegate');
    }

    
}
