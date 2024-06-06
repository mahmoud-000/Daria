<?php

namespace Modules\Company\Http\Requests;

use App\Traits\ValidationErrorResponseTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class StoreCompanyRequest extends FormRequest
{
    use ValidationErrorResponseTrait;
    public function rules()
    {
        return [
            'name'          => ['required', 'string', 'min:3', 'max:100', Rule::unique('companies', 'name')->whereNull('deleted_at')->ignore($this->company)],
            'is_active'     => ['nullable', 'boolean'],
            'remarks'       => ['string', 'nullable'],

            'branches'            => ['required', 'array', 'present'],
            'branches.*.name'     => ['distinct', 'required', 'string', 'min:3', 'max:100', Rule::unique('branches', 'name')->whereNull('deleted_at')->ignore($this->id, 'company_id')],
            'branches.*.is_main'  => ['sometimes', 'boolean'],
            'branches.*.is_active'  => ['required', 'boolean'],
            'branches.*.country' => ['string', 'nullable'],
            'branches.*.city' => ['string', 'nullable'],
            'branches.*.state' => ['string', 'nullable'],
            'branches.*.zip' => ['string', 'nullable'],
            'branches.*.address' => ['string', 'nullable'],
            'branches.*.email' => ['distinct', 'sometimes', 'nullable', 'email', Rule::unique('branches', 'email')->whereNull('deleted_at')],
            'branches.*.phone' => ['distinct', 'sometimes', 'nullable', 'string'],
            'branches.*.mobile' => ['distinct', 'sometimes', 'nullable', 'string'],

            'logo' => ['sometimes', 'array', 'nullable'],
        ];
    }

    public function authorize()
    {
        return auth()->user()->is_owner || Gate::allows('create-company');
    }
}
