<?php

namespace Modules\Auth\Http\Requests;

use App\Traits\ValidationErrorResponseTrait;
use Illuminate\Foundation\Http\FormRequest;

class ForgetPasswordRequest extends FormRequest
{
    use ValidationErrorResponseTrait;
    public function rules()
    {
        return [
            'email' => ['required', 'email']
        ];
    }

    public function authorize()
    {
        return true;
    }
}
