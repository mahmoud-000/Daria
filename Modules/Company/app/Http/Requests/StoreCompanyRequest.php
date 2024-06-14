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
            'name'          => ['required', 'string', 'min:3', 'max:100', Rule::unique('companies', 'name')->withoutTrashed()->ignore($this->company)],
            'is_active'     => ['required', 'boolean'],
            'currency'     => ['required', 'string', 'min:3', 'max:3'],
            'remarks'       => ['string', 'nullable'],

            'branches'            => ['required', 'array', 'present'],
            'branches.*.name'     => ['distinct', 'required', 'string', 'min:3', 'max:100', Rule::unique('branches', 'name')->withoutTrashed()->whereNull('company_id')],
            'branches.*.is_main'  => ['sometimes', 'boolean'],
            'branches.*.is_active'  => ['required', 'boolean'],
            'branches.*.country' => ['string', 'nullable'],
            'branches.*.city' => ['string', 'nullable'],
            'branches.*.state' => ['string', 'nullable'],
            'branches.*.zip' => ['string', 'nullable'],
            'branches.*.address' => ['string', 'nullable'],
            'branches.*.email' => ['distinct', 'sometimes', 'nullable', 'email', Rule::unique('branches', 'email')->withoutTrashed()->ignore($this->company, 'company_id')],
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
