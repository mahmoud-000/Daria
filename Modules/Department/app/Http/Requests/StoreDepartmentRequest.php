<?php

namespace Modules\Department\Http\Requests;

use App\Traits\ValidationErrorResponseTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class StoreDepartmentRequest extends FormRequest
{
    use ValidationErrorResponseTrait;
    public function rules()
    {
        return [
            'name'          => ['required', 'string', 'min:3', 'max:100', Rule::unique('departments', 'name')->whereNull('deleted_at')->ignore($this->id)],
            'is_active'     => ['nullable', 'boolean'],
            'department_id'       => ['sometimes', 'integer', 'nullable'],
            'remarks'       => ['string', 'nullable'],
            'logo'          => ['sometimes', 'array', 'nullable'],
        ];
    }

    public function authorize()
    {
        return auth()->user()->is_owner || Gate::allows('create-department');
    }
}
