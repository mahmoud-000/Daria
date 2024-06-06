<?php

namespace Modules\Stock\Http\Requests;

use App\Rules\WithOutSpaces;
use App\Traits\ValidationErrorResponseTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class StoreStockRequest extends FormRequest
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
        return auth()->user()->is_owner || Gate::allows('create-stock');
    }
}
