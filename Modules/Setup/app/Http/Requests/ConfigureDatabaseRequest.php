<?php

namespace Modules\Setup\Http\Requests;

use App\Rules\WithOutSpaces;
use Illuminate\Foundation\Http\FormRequest;

class ConfigureDatabaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'db_host' => ['required', new WithOutSpaces],
            'db_port' => ['required', 'numeric'],
            'db_name' => ['required', new WithOutSpaces],
            'db_user' => 'required',
            'db_password' => 'nullable'
        ];
    }
}
