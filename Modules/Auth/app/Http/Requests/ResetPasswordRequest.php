<?php

namespace Modules\Auth\Http\Requests;

use App\Traits\ValidationErrorResponseTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class ResetPasswordRequest extends FormRequest
{
    use ValidationErrorResponseTrait;
    public function rules()
    {
        return [
            'email' => ['required', 'email'],
            'token' => ['required'],
            'password' => ['required', 'confirmed',  Password::min(8)
            ->mixedCase()
            ->letters()
            ->numbers()
            ->symbols()
            ->uncompromised()],
        ];
    }

    public function authorize()
    {
        return true;
    }
}
