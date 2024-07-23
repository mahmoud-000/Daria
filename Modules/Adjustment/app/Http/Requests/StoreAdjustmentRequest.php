<?php

namespace Modules\Adjustment\Http\Requests;

use App\Enums\ItemTypesEnum;
use App\Enums\ProductTypesEnum;
use App\Traits\ValidationErrorResponseTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class StoreAdjustmentRequest extends FormRequest
{
    use ValidationErrorResponseTrait;
    public function rules()
    {
        return [
            'warehouse_id' => ['required', 'integer'],
            'remarks'       => ['string', 'nullable'],
            'date' => ['required', 'date'],
            'grand_total' => ['required', 'numeric'],

            'pipeline_id' => ['sometimes', 'nullable', 'integer'],
            'stage_id' => ['sometimes', 'nullable', 'integer'],
            
            'details' => ['required', 'array'],
            'details.*.warehouse_id' => ['required', 'integer'],
            'details.*.movement' => ['required', 'integer'],
            'details.*.item_id' => ['required', 'integer'],
            'details.*.variant_id' => ['sometimes', 'integer', 'nullable'],
            'details.*.patch_id' => ['sometimes', 'integer', 'nullable'],
            'details.*.unit_id' => ['required', 'integer'],
            'details.*.amount' => ['required', 'numeric', 'min:0'],
            'details.*.discount' => ['required', 'numeric', 'min:0'],
            'details.*.discount_type' => ['required', 'integer', Rule::in([1, 2])],
            'details.*.tax' => ['required', 'numeric', 'min:0'],
            'details.*.tax_type' => ['required', 'integer', Rule::in([1, 2])],
            'details.*.total' => ['required', 'numeric'],
            'details.*.quantity' => ['required', 'numeric', 'min:1'],
            'details.*.production_date' => ['nullable', 'date', 'date_format:Y-m-d'],
            'details.*.expired_date' => ['nullable', 'date', 'date_format:Y-m-d', 'after:details.*.production_date'],
            'details.*.product_type' => ['required', 'integer', Rule::in([ProductTypesEnum::STOCK_ITEM->value, ProductTypesEnum::CONSUMER_ITEM->value])],
            'details.*.type' => ['required', 'integer', Rule::in([ItemTypesEnum::STANDARD->value, ItemTypesEnum::VARIABLE->value, ItemTypesEnum::SERVICE->value])],

            'adjustment_documents' => ['sometimes', 'array', 'nullable'],
        ];
    }

    public function authorize()
    {
        return auth()->user()->is_owner || Gate::allows('create-adjustment');
    }
}
