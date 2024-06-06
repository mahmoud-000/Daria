<?php

namespace Modules\Unit\Http\Requests;

use App\Traits\ValidationErrorResponseTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class StoreUnitRequest extends FormRequest
{
    use ValidationErrorResponseTrait;
    public function rules()
    {
        return [
            'name'          => ['required', 'string', 'min:3', 'max:100', Rule::unique('units', 'name')->whereNull('deleted_at')->ignore($this->unit)],
            'short_name'    => ['required', 'min:1', 'max:50', Rule::unique('units', 'short_name')->whereNull('deleted_at')->ignore($this->unit)],
            'unit_id'       => ['sometimes', 'integer', 'nullable'],
            'operator'      => [
                'sometimes', 'nullable',
                Rule::in(['*', '/']), Rule::requiredIf(!!$this->unit_id)
            ],
            'operator_value' => [
                'sometimes', 'numeric', 'nullable',
                Rule::requiredIf(!!$this->unit_id)
            ],
            'is_active'     => ['nullable', 'boolean'],
            'remarks'       => ['string', 'nullable'],
        ];
    }

    public function authorize()
    {
        return auth()->user()->is_owner || Gate::allows('create-unit');
    }
}
