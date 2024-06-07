<?php

namespace Modules\User\Http\Requests;

use App\Traits\ValidationErrorResponseTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class StoreUserRequest extends FormRequest
{
    use ValidationErrorResponseTrait;
    public function rules()
    {
        return [
            'username'  => ['required', 'string', 'min:8', 'max:100', Rule::unique('users', 'username')->whereNull('deleted_at')->ignore($this->user)],
            'password'  => ['required', 'confirmed', Password::min(8)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()],

            'email'     => [Rule::requiredIf($this->send_notify), 'nullable', 'email', Rule::unique('users', 'email')->whereNull('deleted_at')->ignore($this->user)],
            'firstname' => ['nullable', 'string', 'min:3', 'max:50'],
            'lastname'  => ['nullable', 'string', 'min:3', 'max:50'],

            'gender'        => ['nullable', 'integer'],
            'date_of_birth' => ['nullable', 'string'],
            'date_of_joining' => ['nullable', 'string'],
            'is_active'     => ['required', 'boolean'],
            'remarks'       => ['string', 'nullable'],

            'send_notify'    => ['sometimes', 'boolean'],

            'roles'         => ['nullable', 'array'],
            'permissions'   => ['sometimes', 'array'],

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
        ];
    }

    public function authorize()
    {
        return auth()->user()->is_owner || Gate::allows('create-user');
    }
}
