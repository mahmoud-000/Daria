<?php

namespace Modules\Variant\Http\Requests;

use App\Traits\ValidationErrorResponseTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class StoreVariantRequest extends FormRequest
{
    use ValidationErrorResponseTrait;
    public function rules()
    {
        return [
            'name'       => ['required', 'string', 'min:3', 'max:100', Rule::unique('variants', 'name')->withoutTrashed()->where('item_id', $this->item_id)],
            'code'       => ['required', 'min:8', 'string', Rule::unique('variants', 'code')->withoutTrashed()->where('item_id', $this->item_id)],
            'sku'       => ['required', 'min:8', 'string', Rule::unique('variants', 'sku')->withoutTrashed()->where('item_id', $this->item_id)],
            'cost'       => ['required', 'numeric', 'min:0'],
            'price'      => ['required', 'numeric', 'min:0'],
            'is_active'     => ['required', 'boolean'],
            'item_id'       => ['required', 'integer'],
            'remarks'       => ['string', 'nullable'],
            'image'         => ['sometimes', 'array', 'nullable'],
        ];
    }

    public function authorize()
    {
        return auth()->user()->is_owner || Gate::allows('create-variant');
    }
}
