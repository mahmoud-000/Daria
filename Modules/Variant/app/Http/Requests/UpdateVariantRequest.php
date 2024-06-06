<?php

namespace Modules\Variant\Http\Requests;

use App\Rules\WithOutSpaces;
use App\Traits\ValidationErrorResponseTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class UpdateVariantRequest extends FormRequest
{
    use ValidationErrorResponseTrait;
    public function rules()
    {
        return [
            'name'       => ['required', 'string', 'min:3', 'max:100'],
            'code'       => ['required', 'min:8', 'string', Rule::unique('variants', 'code')->whereNull('deleted_at')->ignore($this->variant)],
            'cost'       => ['required', 'numeric', 'min:0'],
            'price'      => ['required', 'numeric', 'min:0'],
            'color'      => ['required', 'string', new WithOutSpaces],
            'item_id'       => ['required', 'integer'],
        ];
    }

    public function authorize()
    {
        return auth()->user()->is_owner || Gate::allows('edit-variant');
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
