<?php

namespace Modules\PurchaseReturn\Http\Requests;

use App\Traits\ValidationErrorResponseTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class StorePurchaseReturnRequest extends FormRequest
{
    use ValidationErrorResponseTrait;
    public function rules()
    {
        return [
            'doc_invoice_number'  => ['sometimes', 'nullable', Rule::unique('purchaseReturns', 'doc_invoice_number')->withoutTrashed()->ignore($this->purchaseReturn)],
            'supplier_id' => ['required', 'integer'],
            'warehouse_id' => ['required', 'integer'],
            'remarks'       => ['string', 'nullable'],
            'date' => ['required', 'date'],
            'tax' => ['required', 'numeric', 'min:0'],
            // 'tax_net' => ['required', 'numeric'],
            'paid_amount' => ['required', 'numeric'],
            'pipeline_id' => ['required', 'integer'],
            'stage_id' => ['required', 'integer'],
            'grand_total' => ['required', 'numeric'],
            'discount_type' => ['required', 'integer', Rule::in([1, 2])],
            'discount' => ['required', 'numeric', 'min:0'],
            'delegate_id' => ['sometimes', 'integer', 'nullable'],
            'commission_type' => ['required', 'integer', Rule::in([1, 2])],
            'shipping' => ['required', 'numeric', 'min:0'],
            'other_expenses' => ['required', 'numeric', 'min:0'],
            
            'details' => ['required', 'array'],
            'details.*.warehouse_id' => ['required', 'integer'],
            'details.*.item_id' => ['required', 'integer'],
            'details.*.variant_id' => ['sometimes', 'integer', 'nullable'],
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
            'details.*.product_type' => ['required', 'integer', Rule::in([1, 2])],

            'payments' => ['sometimes', 'array'],
            'payments.*.date' => ['required', 'date'],
            // 'payments.*.time' => ['required', 'date_format:H:i:s'],
            'payments.*.type' => ['required', 'integer', Rule::in([1, 2, 3, 4, 5, 6])],
            'payments.*.received_amount' => ['required', 'numeric', 'min:1'],
            'payments.*.amount' => ['required', 'numeric', 'min:1'],
            'payments.*.note' => ['sometimes', 'string', 'nullable'],

            'purchaseReturn_documents' => ['sometimes', 'array', 'nullable'],
        ];
    }

    public function authorize()
    {
        return auth()->user()->is_owner || Gate::allows('create-purchaseReturn');
    }
}
