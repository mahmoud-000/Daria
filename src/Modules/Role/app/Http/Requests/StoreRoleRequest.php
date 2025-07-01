<?php

namespace Modules\Role\Http\Requests;

use App\Traits\ValidationErrorResponseTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class StoreRoleRequest extends FormRequest
{
    use ValidationErrorResponseTrait;
    public function rules()
    {
        return [
            'name'  => ['required', 'string', 'min:4', 'max:100', Rule::unique('roles', 'name')->withoutTrashed()->ignore($this->role)],
            'is_active'     => ['required', 'boolean'],
            'permissions'   => ['required', 'array'],
        ];
    }

    public function authorize()
    {
        return auth()->user()->is_owner || Gate::allows('create-role');
    }
}
