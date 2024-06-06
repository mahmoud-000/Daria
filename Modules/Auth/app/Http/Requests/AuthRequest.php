<?php

namespace Modules\Auth\Http\Requests;

use App\Traits\ValidationErrorResponseTrait;
use Illuminate\Foundation\Http\FormRequest;

class AuthRequest extends FormRequest
{
    use ValidationErrorResponseTrait;
    
    public function rules()
    {
        return [
            'username'  => ['required'],
            'password'  => ['required'],
            'device'    => ['required'],
            'remember'  => ['required', 'boolean'],
        ];
    }

    public function authorize()
    {
        return true;
    }
}
