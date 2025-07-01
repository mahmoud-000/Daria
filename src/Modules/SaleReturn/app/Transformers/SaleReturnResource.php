<?php

namespace Modules\SaleReturn\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Detail\Transformers\DetailResource;
use Modules\Payment\Transformers\PaymentResource;
use Modules\Customer\Transformers\CustomerResource;
use Modules\Delegate\Transformers\DelegateResource;
use Modules\Upload\Transformers\UploadResource;

class SaleReturnResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'ref' => sprintf('%07d', $this->id),
            'doc_invoice_number' => $this->doc_invoice_number,
            'user_id' => $this->user_id,
            'customer_id' => $this->customer_id,
            'customer' => $this->whenLoaded('customer') ? CustomerResource::make($this->whenLoaded('customer')) : null,
            'delegate_id' => $this->delegate_id,
            'delegate' => $this->whenLoaded('delegate') ? DelegateResource::make($this->whenLoaded('delegate')) : null,
            'warehouse_id' => $this->warehouse_id,
            'warehouse' => $this->whenLoaded('warehouse'),
            'remarks' => $this->remarks ?? '',
            'date' => $this->date,
            'tax' => floatval($this->tax),
            'paid_amount' => floatval($this->paid_amount),
            'pipeline_id' => $this->pipeline_id,
            'pipeline' => $this->whenLoaded('pipeline'),
            'stage_id' => $this->stage_id,
            'stage' => $this->whenLoaded('stage'),
            'payment_status' => $this->payment_status,
            'grand_total' => floatval($this->grand_total),
            // 'due' => floatval($this->grand_total) - floatval($this->whenLoaded('payments')?->sum('amount')),
            'discount_type' => $this->discount_type,
            'discount' => floatval($this->discount),
            'commission_type' => $this->commission_type,
            'shipping' => floatval($this->shipping),
            'other_expenses' => floatval($this->other_expenses),
            'details' => DetailResource::collection($this->whenLoaded('details')),
            'payments' => PaymentResource::collection($this->whenLoaded('payments')),
            'created_at' => $this->created_at->diffForHumans(),
            'updated_at' => $this->updated_at->diffForHumans(),
            'saleReturn_documents' => $this->whenLoaded('media') && $this->media_count
                ? $this->getMedia('saleReturns')->map(function ($media) {
                    return (new UploadResource($media));
                }) : [],
        ];
    }
}
