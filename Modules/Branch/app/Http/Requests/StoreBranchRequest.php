<?php

namespace Modules\Branch\Http\Requests;

use App\Traits\ValidationErrorResponseTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class StoreBranchRequest extends FormRequest
{
    use ValidationErrorResponseTrait;
    public function rules()
    {
        return [
            'name'     => ['required', 'string', 'min:3', 'max:100', Rule::unique('branches', 'name')->withoutTrashed()->where('company_id', $this->company_id)],
            'is_main'  => ['required', 'boolean'],
            'is_active'  => ['required', 'boolean'],
            'country' => ['sometimes', 'string', 'nullable'],
            'city' => ['sometimes', 'string', 'nullable'],
            'state' => ['sometimes', 'string', 'nullable'],
            'zip' => ['sometimes', 'string', 'nullable'],
            'address' => ['sometimes', 'string', 'nullable'],
            'email' => ['sometimes', 'nullable', 'email', Rule::unique('branches', 'email')->withoutTrashed()],
            'phone' => ['sometimes', 'nullable', 'string', Rule::unique('branches', 'phone')->withoutTrashed()],
            'mobile' => ['sometimes', 'nullable', 'string', Rule::unique('branches', 'mobile')->withoutTrashed()],
            'company_id'       => ['required', 'integer'],
        ];
    }

    public function authorize()
    {
        return auth()->user()->is_owner || Gate::allows('create-branch');
    }
}
