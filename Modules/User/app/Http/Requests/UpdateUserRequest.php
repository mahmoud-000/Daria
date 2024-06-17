<?php

namespace Modules\User\Http\Requests;

use App\Traits\ValidationErrorResponseTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateUserRequest extends FormRequest
{
    use ValidationErrorResponseTrait;
    public function rules()
    {
        return [
            'username'  => ['required', 'string', 'min:8', 'max:100', Rule::unique('users', 'username')->withoutTrashed()->ignore($this->user)],
            'password'  => ['sometimes', 'nullable', 'confirmed', Password::min(8)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()],

            'email'     => [Rule::requiredIf($this->send_notify), 'nullable', 'email', Rule::unique('users', 'email')->withoutTrashed()->ignore($this->user)],
            'firstname' => ['nullable', 'string', 'min:3', 'max:50'],
            'lastname'  => ['nullable', 'string', 'min:3', 'max:50'],

            'gender'        => ['nullable', 'integer'],
            'date_of_birth' => ['nullable', 'string'],
            'date_of_joining' => ['nullable', 'string'],
            'is_active'     => ['required', 'boolean'],
            'remarks'       => ['string', 'nullable'],

            'send_notify'    => ['sometimes', 'boolean'],

            'role_ids'         => ['nullable', 'array'],
            'permissions'   => ['sometimes', 'array'],

            'locations'     => ['sometimes', 'array', 'present'],
            'locations.*.name' => ['distinct', 'required', 'string', 'min:3', 'max:100', Rule::unique('locations', 'name')->where('locationable_type', 'User')->ignore($this->user->id, 'locationable_id')],
            'locations.*.country' => ['string', 'nullable'],
            'locations.*.city' => ['string', 'nullable'],
            'locations.*.state' => ['string', 'nullable'],
            'locations.*.zip' => ['string', 'nullable'],
            'locations.*.first_address' => ['string', 'nullable'],
            'locations.*.second_address' => ['string', 'nullable'],

            'contacts' => ['sometimes', 'array', 'present'],
            'contacts.*.name' => ['distinct', 'required', 'string', 'min:3', 'max:100', Rule::unique('contacts', 'name')->where('contactable_type', 'User')->ignore($this->user->id, 'contactable_id')],
            'contacts.*.email' => ['distinct', 'sometimes', 'nullable', 'email', Rule::unique('contacts', 'email')->where('contactable_type', 'User')->ignore($this->user->id, 'contactable_id')],
            'contacts.*.phone' => ['distinct', 'sometimes', 'nullable', 'string', Rule::unique('contacts', 'phone')->where('contactable_type', 'User')->ignore($this->user->id, 'contactable_id')],
            'contacts.*.mobile' => ['distinct', 'sometimes', 'nullable', 'string', Rule::unique('contacts', 'mobile')->where('contactable_type', 'User')->ignore($this->user->id, 'contactable_id')],

            'avatar' => ['sometimes', 'array', 'nullable'],
        ];
    }

    public function authorize()
    {
        return auth()->user()->is_owner || Gate::allows('edit-user');
    }
}
