<?php

namespace Modules\Quotation\Http\Requests;

use App\Traits\ValidationErrorResponseTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class StoreQuotationRequest extends FormRequest
{
    use ValidationErrorResponseTrait;
    public function rules()
    {
        return [
            'customer_id' => ['required', 'integer'],
            'warehouse_id' => ['required', 'integer'],
            'remarks'       => ['string', 'nullable'],
            'date' => ['required', 'date'],
            'tax' => ['required', 'numeric'],
            'tax_net' => ['required', 'numeric'],
            'paid_amount' => ['required', 'numeric'],
            'pipeline_id' => ['required', 'integer'],
            'stage_id' => ['required', 'integer'],
            'reason_id' => ['sometimes', 'nullable', 'integer'],
            'grand_total' => ['required', 'numeric'],
            'discount' => ['required', 'numeric'],
            'shipping_type' => ['required', 'integer'],
            'shipper_id' => ['integer', 'nullable', Rule::requiredIf($this->shipping_type !== 3)],
            'shipping' => ['numeric', Rule::requiredIf($this->shipping_type === 3)],
            'details' => ['required', 'array'],
            'details.*.amount' => ['required', 'numeric', 'min:0'],
            'details.*.discount' => ['required', 'numeric', 'min:0'],
            'details.*.discount_type' => ['required', 'integer', Rule::in([1, 2])],
            'details.*.tax' => ['required', 'numeric', 'min:0'],
            'details.*.tax_type' => ['required', 'integer', Rule::in([1, 2])],
            'details.*.unit_id' => ['required', 'integer'],
            'details.*.total' => ['required', 'numeric'],
            'details.*.quantity' => ['required', 'numeric'],
            'deletedDetails' => ['sometimes', 'array'],
        ];
    }

    public function authorize()
    {
        return auth()->user()->is_owner || Gate::allows('create-quotation');
    }
}
