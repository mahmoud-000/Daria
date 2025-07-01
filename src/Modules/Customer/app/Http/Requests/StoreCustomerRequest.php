<?php

namespace Modules\Customer\Http\Requests;

use App\Enums\ICTypesEnum;
use App\Traits\ValidationErrorResponseTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class StoreCustomerRequest extends FormRequest
{
    use ValidationErrorResponseTrait;
    public function rules()
    {
        return [
            'fullname'  => ['required', 'string', 'min:8', 'max:100', Rule::unique('customers', 'fullname')->withoutTrashed()->ignore($this->customer)],
            'email'     => ['nullable', 'email', Rule::unique('customers', 'email')->withoutTrashed()->ignore($this->customer)],
            'remarks'       => ['string', 'nullable'],
            'is_active'      => ['sometimes', 'boolean'],
            'type'      => ['required', 'integer'],
            'company_name'  => [Rule::requiredIf(!$this->type || $this->type === ICTypesEnum::COMPANY->value), 'nullable', 'string', 'min:8', 'max:100', Rule::unique('customers', 'company_name')->withoutTrashed()->ignore($this->customer)],
            
            'locations'     => ['sometimes', 'array', 'present'],
            'locations.*.name' => ['distinct', 'required', 'string', 'min:3', 'max:100', Rule::unique('locations', 'name')->where('locationable_type', 'Customer')->whereNull('locationable_id')],
            'locations.*.country' => ['string', 'nullable'],
            'locations.*.city' => ['string', 'nullable'],
            'locations.*.state' => ['string', 'nullable'],
            'locations.*.zip' => ['string', 'nullable'],
            'locations.*.first_address' => ['string', 'nullable'],
            'locations.*.second_address' => ['string', 'nullable'],

            'contacts' => ['sometimes', 'array', 'present'],
            'contacts.*.name' => ['distinct', 'required', 'string', 'min:3', 'max:100', Rule::unique('contacts', 'name')->where('contactable_type', 'Customer')->whereNull('contactable_id')],
            'contacts.*.email' => ['distinct', 'sometimes', 'nullable', 'email', Rule::unique('contacts', 'email')->where('contactable_type', 'Customer')],
            'contacts.*.phone' => ['distinct', 'sometimes', 'nullable', 'string', Rule::unique('contacts', 'phone')->where('contactable_type', 'Customer')],
            'contacts.*.mobile' => ['distinct', 'sometimes', 'nullable', 'string', Rule::unique('contacts', 'mobile')->where('contactable_type', 'Customer')],

            'avatar' => ['sometimes', 'array', 'nullable'],
        ];
    }

    public function authorize()
    {
        return auth()->user()->is_owner || Gate::allows('create-customer');
    }

    
}
