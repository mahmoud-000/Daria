<?php

namespace Modules\Warehouse\Http\Requests;

use App\Traits\ValidationErrorResponseTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateWarehouseRequest extends FormRequest
{
    use ValidationErrorResponseTrait;
    public function rules()
    {
        return [
            'name'         => ['required', 'string', 'min:3', 'max:100', Rule::unique('warehouses', 'name')->whereNull('deleted_at')->ignore($this->warehouse)],
            'email'     => ['sometimes', 'nullable', 'email', Rule::unique('warehouses', 'email')->whereNull('deleted_at')->ignore($this->warehouse)],
            'phone' => ['sometimes', 'nullable', 'string'],
            'mobile' => ['sometimes', 'nullable', 'string'],
            'country' => ['string', 'nullable'],
            'city' => ['string', 'nullable'],
            'state' => ['string', 'nullable'],
            'zip' => ['string', 'nullable'],
            'first_address' => ['string', 'nullable'],
            'second_address' => ['string', 'nullable'],
            'is_active'    => ['nullable', 'boolean'],
            'remarks'      => ['string', 'nullable', 'max:255'],
            'logo'         => ['sometimes', 'array', 'nullable'],
        ];
    }

    public function authorize()
    {
        return auth()->user()->is_owner || Gate::allows('edit-user');
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
