<?php

namespace Modules\Auth\Http\Requests;

use App\Traits\ValidationErrorResponseTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateProfileRequest extends FormRequest
{
    use ValidationErrorResponseTrait;
    public function rules()
    {
        return [
            'username'  => ['required', 'string', 'min:8', 'max:100', Rule::unique('users', 'username')->whereNull('deleted_at')->ignore($this->id)],
            'password'  => ['sometimes', 'nullable', 'confirmed', Password::min(8)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()],

            'email'     => [Rule::requiredIf($this->send_notify), 'nullable', 'email', Rule::unique('users', 'email')->whereNull('deleted_at')->ignore($this->id)],
            'firstname' => ['nullable', 'string', 'min:3', 'max:50'],
            'lastname'  => ['nullable', 'string', 'min:3', 'max:50'],

            'gender'        => ['nullable', 'integer'],
            'date_of_birth' => ['nullable', 'string'],
            'date_of_joining' => ['nullable', 'string'],
            'is_active'     => ['nullable', 'boolean'],
            'remarks'       => ['string', 'nullable'],

            'locations'     => ['sometimes', 'array', 'present'],
            'locations.*.country' => ['string', 'nullable'],
            'locations.*.city' => ['string', 'nullable'],
            'locations.*.state' => ['string', 'nullable'],
            'locations.*.zip' => ['string', 'nullable'],
            'locations.*.first_address' => ['string', 'nullable'],
            'locations.*.second_address' => ['string', 'nullable'],

            'contacts' => ['sometimes', 'array', 'present'],
            'contacts.*.email' => ['sometimes', 'nullable', 'email', Rule::unique('contacts', 'email')->where('contactable_type', 'User')->whereNull('deleted_at')->ignore($this->id, 'contactable_id')],
            'contacts.*.firstname' => ['sometimes', 'nullable', 'string'],
            'contacts.*.lastname' => ['sometimes', 'nullable', 'string'],
            'contacts.*.phone' => ['sometimes', 'nullable', 'string'],
            'contacts.*.mobile' => ['sometimes', 'nullable', 'string'],

            'avatar' => ['sometimes', 'array', 'nullable'],
        ];
    }

    public function authorize()
    {
        return Gate::any(['edit-user-profile', 'edit-customer-profile']);
    }

    protected function prepareForValidation()
    {
        if ($this->password == null) {
            $this->request->remove('password');
            $this->request->remove('password_confirmation');
            // dd($this->request);
        }
    }
}
