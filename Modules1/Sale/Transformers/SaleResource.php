<?php

namespace Modules\Sale\Transformers;

use App\Modules\PipelineStage\PipelineStageResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Detail\Transformers\DetailResource;
use Modules\Payment\Transformers\PaymentResource;

class SaleResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            // 'base64' => $this->base64,
            'id' => $this->id,
            'user_id' => $this->user_id,
            'supplier_id' => $this->supplier_id,
            'supplier' => $this->whenLoaded('supplier')->name,
            'supplier_email' => $this->whenLoaded('supplier')->email,
            'supplier_mobile' => $this->whenLoaded('supplier')->mobile,
            'supplier_phone' => $this->whenLoaded('supplier')->phone,
            'supplier_first_address' => $this->whenLoaded('supplier')->first_address,
            'supplier_second_address' => $this->whenLoaded('supplier')->second_address,
            'warehouse_id' => $this->warehouse_id,
            'warehouse' => $this->whenLoaded('warehouse')->name,
            'remarks' => $this->remarks ?? '',
            'date' => $this->date,
            'ref' => $this->ref,
            'tax' => floatval($this->tax),
            'tax_net' => floatval($this->tax_net),
            'paid_amount' => floatval($this->paid_amount),
            'pipeline_name' => $this->whenLoaded('pipeline')->name ?? 'N/D',
            'pipeline_id' => $this->pipeline_id,
            'stage' => PipelineStageResource::make($this->pipelineStage($this->pipeline_id, $this->stage_id)),
            'stage_id' => $this->stage_id,
            'reason_id' => $this->reason_id,
            'reason_name' => $this->whenLoaded('reason')->name ?? 'N/D',
            'payment_status' => +$this->payment_status,
            'grand_total' => floatval($this->grand_total),
            'due' => floatval($this->grand_total) - floatval($this->whenLoaded('payments')->sum('amount')),
            'discount' => floatval($this->discount),
            'shipping' => floatval($this->shipping),
            'shipping_type' => $this->shipping_type,
            'shipper_id' => $this->shipper_id,
            'details' => DetailResource::collection($this->whenLoaded('details')),
            'payments' => PaymentResource::collection($this->whenLoaded('payments')),
        ];
    }
}
