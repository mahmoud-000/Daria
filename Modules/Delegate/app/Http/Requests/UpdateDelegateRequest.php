<?php

namespace Modules\Delegate\Http\Requests;

use App\Enums\ICTypesEnum;
use App\Traits\ValidationErrorResponseTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class UpdateDelegateRequest extends FormRequest
{
    use ValidationErrorResponseTrait;
    public function rules()
    {
        return [
            'fullname'  => ['required', 'string', 'min:8', 'max:100', Rule::unique('delegates', 'fullname')->whereNull('deleted_at')->ignore($this->delegate)],
            'company_name'  => [Rule::requiredIf(!$this->type || $this->type === ICTypesEnum::COMPANY->value), 'nullable', 'string', 'min:8', 'max:100', Rule::unique('delegates', 'company_name')->whereNull('deleted_at')->ignore($this->delegate)],
            'email'     => ['nullable', 'email', Rule::unique('delegates', 'email')->whereNull('deleted_at')->ignore($this->delegate)],
            'remarks'       => ['string', 'nullable'],
            'is_active'      => ['sometimes', 'boolean'],

            'locations'     => ['sometimes', 'array', 'present'],
            'locations.*.name' => ['required', 'string', 'min:3', 'max:100', Rule::unique('locations', 'name')->where('locationable_type', 'User')->whereNull('deleted_at')->ignore($this->id, 'locationable_id')],
            'locations.*.country' => ['string', 'nullable'],
            'locations.*.city' => ['string', 'nullable'],
            'locations.*.state' => ['string', 'nullable'],
            'locations.*.zip' => ['string', 'nullable'],
            'locations.*.first_address' => ['string', 'nullable'],
            'locations.*.second_address' => ['string', 'nullable'],

            'contacts' => ['sometimes', 'array', 'present'],
            'contacts.*.name' => ['required', 'string', 'min:3', 'max:100', Rule::unique('contacts', 'name')->where('contactable_type', 'User')->whereNull('deleted_at')->ignore($this->id, 'contactable_id')],
            'contacts.*.email' => ['sometimes', 'nullable', 'email', Rule::unique('contacts', 'email')->where('contactable_type', 'User')->whereNull('deleted_at')->ignore($this->id, 'contactable_id')],
            'contacts.*.phone' => ['sometimes', 'nullable', 'string'],
            'contacts.*.mobile' => ['sometimes', 'nullable', 'string'],

            'avatar' => ['sometimes', 'array', 'nullable'],

            'type'          => ['required', 'integer'],
            'commission_type'          => ['required', 'integer'],
            'commission'          => ['required', 'numeric', 'min:0'],
        ];
    }

    public function authorize()
    {
        return auth()->user()->is_owner || Gate::allows('edit-delegate');
    }

    
}
