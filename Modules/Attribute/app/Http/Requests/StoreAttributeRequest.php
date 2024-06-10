<?php

namespace Modules\Attribute\Http\Requests;

use App\Traits\ValidationErrorResponseTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class StoreAttributeRequest extends FormRequest
{
    use ValidationErrorResponseTrait;
    public function rules()
    {
        return [
            'name'          => ['required', 'string', 'min:3', 'max:100', Rule::unique('attributes', 'name')->whereNull('deleted_at')->ignore($this->attribute)],
            'is_active'     => ['required', 'boolean'],
            'remarks'       => ['string', 'nullable']
        ];
    }

    public function authorize()
    {
        return auth()->user()->is_owner || Gate::allows('create-attribute');
    }
}
