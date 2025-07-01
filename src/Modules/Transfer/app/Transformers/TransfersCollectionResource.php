<?php

namespace Modules\Transfer\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransfersCollectionResource extends JsonResource
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
            'from_warehouse' => $this->whenLoaded('warehouseFrom') ? $this->warehouseFrom->name : null,
            'to_warehouse' => $this->whenLoaded('warehouseTo') ? $this->warehouseTo->name : null,
            'pipeline' => $this->whenLoaded('pipeline') ? $this->pipeline->name : null,
            'grand_total' => floatval($this->grand_total),
            'created_at' => $this->created_at->diffForHumans(),
            'updated_at' => $this->updated_at->diffForHumans(),
        ];
    }
}
