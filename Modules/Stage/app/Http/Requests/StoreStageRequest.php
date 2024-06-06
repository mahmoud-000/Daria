<?php

namespace Modules\Stage\Http\Requests;

use App\Rules\WithOutSpaces;
use App\Traits\ValidationErrorResponseTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreStageRequest extends FormRequest
{
    use ValidationErrorResponseTrait;
    public function rules()
    {
        return [
            'name'     => ['required', 'string', 'min:3', 'max:100'],
            'complete' => ['required', 'numeric', 'min:0', 'max:100'],
            'color'    => ['required', 'string', new WithOutSpaces],
            'default'  => ['sometimes', 'boolean'],
        ];
    }

    public function authorize()
    {
        return auth()->user()->is_owner || Gate::allows('create-stage');
    }
}
