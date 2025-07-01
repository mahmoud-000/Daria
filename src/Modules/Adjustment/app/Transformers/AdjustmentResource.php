<?php

namespace Modules\Adjustment\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Detail\Transformers\DetailResource;
use Modules\Payment\Transformers\PaymentResource;
use Modules\Supplier\Transformers\SupplierResource;
use Modules\Delegate\Transformers\DelegateResource;
use Modules\Upload\Transformers\UploadResource;

class AdjustmentResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'ref' => sprintf('%07d', $this->id),
            'items' => $this->items,
            'user_id' => $this->user_id,
            'warehouse_id' => $this->warehouse_id,
            'warehouse' => $this->whenLoaded('warehouse'),
            'remarks' => $this->remarks ?? '',
            'date' => $this->date,
            'pipeline_id' => $this->pipeline_id,
            'pipeline' => $this->whenLoaded('pipeline'),
            'stage_id' => $this->stage_id,
            'stage' => $this->whenLoaded('stage'),
            'grand_total' => floatval($this->grand_total),
            'details' => DetailResource::collection($this->whenLoaded('details')),
            'created_at' => $this->created_at->diffForHumans(),
            'updated_at' => $this->updated_at->diffForHumans(),
            'adjustment_documents' => $this->whenLoaded('media') && $this->media_count
                ? $this->getMedia('adjustments')->map(function ($media) {
                    return (new UploadResource($media));
                }) : [],
        ];
    }
}
