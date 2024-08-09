<?php

namespace Modules\Department\Http\Requests;

use App\Traits\ValidationErrorResponseTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class UpdateDepartmentRequest extends FormRequest
{
    use ValidationErrorResponseTrait;
    public function rules()
    {
        return [
            'name'         => ['required', 'string', 'min:3', 'max:100', Rule::unique('departments', 'name')->withoutTrashed()->ignore($this->id)],
            'is_active'    => ['required', 'boolean'],
            'department_id'       => ['sometimes', 'integer', 'nullable'],
            'user_id'       => ['sometimes', 'integer', 'nullable'],
            'remarks'      => ['string', 'nullable', 'max:255']
        ];
    }

    public function authorize()
    {
        return auth()->user()->is_owner || Gate::allows('edit-user');
    }
}
