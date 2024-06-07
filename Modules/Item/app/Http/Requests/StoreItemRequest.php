<?php

namespace Modules\Item\Http\Requests;

use App\Enums\ItemTypesEnum;
use App\Rules\WithOutSpaces;
use App\Traits\ValidationErrorResponseTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class StoreItemRequest extends FormRequest
{
    use ValidationErrorResponseTrait;
    public function rules()
    {
        return [
            'name'          => ['required', 'min:3', 'max:100', Rule::unique('items', 'name')->whereNull('deleted_at')->ignore($this->item)],
            'label'         => ['required', 'min:3', 'max:100', 'string', Rule::unique('items', 'label')->whereNull('deleted_at')->ignore($this->item)],
            'item_desc'  => ['sometimes', 'max:255', 'nullable', 'string'],
            'category_id'   => ['required', 'integer', 'nullable'],
            'brand_id'      => ['sometimes', 'integer', 'nullable'],
            'code'          => ['required', 'min:8', 'string', Rule::unique('items', 'code')->whereNull('deleted_at')->ignore($this->item)],
            'barcode_type'  => ['required', 'integer'],
            'cost'          => [Rule::requiredIf(!$this->type || $this->type === ItemTypesEnum::STANDARD->value), 'numeric', 'min:0'],
            'price'         => [Rule::requiredIf(!$this->type || $this->type !== ItemTypesEnum::VARIABLE->value), 'numeric', 'min:0'],
            'unit_id'       => ['nullable', 'integer'],
            'sale_unit_id'  => ['nullable', 'integer'],
            'purchase_unit_id' => ['nullable', 'integer'],
            'tax_type'      => ['required', 'nullable', Rule::requiredIf(!!$this->tax), 'integer', Rule::in([1, 2])],
            'tax'           => ['sometimes', Rule::requiredIf(!!$this->tax_type), 'numeric', 'min:0'],
            'stock_alert'   => ['sometimes', 'integer', 'min:0'],
            'is_active'     => ['required', 'boolean'],
            'is_available_for_purchase'     => ['required', 'boolean'],
            'is_available_for_sale'     => ['required', 'boolean'],
            'is_available_for_edit_in_purchase'     => ['required', 'boolean'],
            'is_available_for_edit_in_sale'     => ['required', 'boolean'],
            'type'              => ['required', 'integer', Rule::in([1, 2, 3])],
            'product_type'      => ['required', 'integer', Rule::in([1, 2])],
            'remarks'       => ['string', 'nullable'],

            'item_images' => ['sometimes', 'array', 'nullable'],

            'variants'              => [Rule::requiredIf($this->type === ItemTypesEnum::VARIABLE->value), 'array'],
            'variants.*.name'       => ['distinct', 'required', 'string', 'min:3', 'max:100'],
            'variants.*.code'       => ['required', 'min:8', 'string', Rule::unique('variants', 'code')->whereNull('deleted_at')],
            'variants.*.cost'       => ['required', 'numeric', 'min:0'],
            'variants.*.price'      => ['required', 'numeric', 'min:0'],
            'variants.*.color'      => ['required', 'string', new WithOutSpaces],
            'variants.*.is_active'     => ['required', 'boolean'],
        ];
    }

    public function authorize()
    {
        return auth()->user()->is_owner || Gate::allows('create-item');
    }
}
