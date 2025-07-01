<?php

namespace Modules\Transfer\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Detail\Transformers\DetailResource;
use Modules\Delegate\Transformers\DelegateResource;
use Modules\Upload\Transformers\UploadResource;

class TransferResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'ref' => sprintf('%07d', $this->id),
            'doc_invoice_number' => $this->doc_invoice_number,
            'user_id' => $this->user_id,
            'delegate_id' => $this->delegate_id,
            'delegate' => $this->whenLoaded('delegate') ? DelegateResource::make($this->whenLoaded('delegate')) : null,
            'from_warehouse_id' => $this->from_warehouse_id,
            'from_warehouse' => $this->whenLoaded('warehouseFrom'),
            'to_warehouse_id' => $this->to_warehouse_id,
            'to_warehouse' => $this->whenLoaded('warehouseTo'),
            'remarks' => $this->remarks ?? '',
            'date' => $this->date,
            'tax' => floatval($this->tax),
            'pipeline_id' => $this->pipeline_id,
            'pipeline' => $this->whenLoaded('pipeline'),
            'stage_id' => $this->stage_id,
            'stage' => $this->whenLoaded('stage'),
            'grand_total' => floatval($this->grand_total),
            'discount_type' => $this->discount_type,
            'discount' => floatval($this->discount),
            'commission_type' => $this->commission_type,
            'shipping' => floatval($this->shipping),
            'other_expenses' => floatval($this->other_expenses),
            'details' => DetailResource::collection($this->whenLoaded('details')),
            'created_at' => $this->created_at->diffForHumans(),
            'updated_at' => $this->updated_at->diffForHumans(),
            'transfer_documents' => $this->whenLoaded('media') && $this->media_count
                ? $this->getMedia('transfers')->map(function ($media) {
                    return (new UploadResource($media));
                }) : [],
        ];
    }
}
