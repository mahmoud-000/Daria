<?php

namespace Modules\Stage\Http\Requests;

use App\Rules\WithOutSpaces;
use App\Traits\ValidationErrorResponseTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateStageRequest extends FormRequest
{
    use ValidationErrorResponseTrait;
    public function rules()
    {
        return [
            'name'     => ['required', 'string', 'max:100'],
            'complete' => ['required', 'numeric', 'min:0','max:100'],
            'color'    => ['required', 'string', new WithOutSpaces],
            'default'  => ['sometimes', 'boolean'],
            'id'       => ['sometimes', 'nullable', 'numeric'],
        ];
    }

    public function authorize()
    {
        return auth()->user()->is_owner || Gate::allows('edit-stage');
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
