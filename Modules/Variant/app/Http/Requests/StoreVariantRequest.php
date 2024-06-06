<?php

namespace Modules\Variant\Http\Requests;

use App\Traits\ValidationErrorResponseTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class StoreVariantRequest extends FormRequest
{
    use ValidationErrorResponseTrait;
    public function rules()
    {
        return [
            //
        ];
    }

    public function authorize()
    {
        return auth()->user()->is_owner || Gate::allows('create-variant');
    }
}
