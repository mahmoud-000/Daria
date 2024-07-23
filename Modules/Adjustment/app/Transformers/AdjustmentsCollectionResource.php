<?php

namespace Modules\Adjustment\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdjustmentsCollectionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'ref' => sprintf('%07d', $this->id),
            'date' => $this->date,
            'items' => $this->items,
            'warehouse' => $this->whenLoaded('warehouse') ? $this->warehouse->name : null,
            'pipeline' => $this->whenLoaded('pipeline') ? $this->pipeline->name : null, 
            'grand_total' => floatval($this->grand_total),
            'created_at' => $this->created_at->diffForHumans(),
            'updated_at' => $this->updated_at->diffForHumans(),
        ];
    }
}
