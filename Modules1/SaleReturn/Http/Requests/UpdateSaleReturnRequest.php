<?php

namespace Modules\SaleReturn\Http\Requests;

use App\Traits\ValidationErrorResponseTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class UpdateSaleReturnRequest extends FormRequest
{
    use ValidationErrorResponseTrait;
    public function rules()
    {
        return [
            'supplier_id' => ['required', 'integer'],
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

            'payments' => ['sometimes', 'array'],
            'payments.*.date' => ['required', 'date'],
            'payments.*.time' => ['required', 'date_format:H:i:s'],
            'payments.*.type' => ['required', 'integer', Rule::in([1, 2, 3, 4, 5, 6])],
            'payments.*.amount' => ['required', 'numeric', 'min:1'],
            'payments.*.note' => ['sometimes', 'string', 'nullable'],

            'deletedDetails' => ['sometimes', 'array'],
            'deletedPayments' => ['sometimes', 'array'],
        ];
    }

    public function authorize()
    {
        return auth()->user()->is_owner || Gate::allows('edit-sale_return');
    }
}
