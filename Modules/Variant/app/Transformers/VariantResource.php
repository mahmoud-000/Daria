<?php

namespace Modules\Variant\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Item\Transformers\ItemResource;

class VariantResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'item_id' => $this->item_id,
            'item' => $this->whenLoaded('item') ? ItemResource::make($this->whenLoaded('item')) : null,
            'name' => $this->name,
            'code' => $this->code,
            'cost' => $this->cost,
            'price' => $this->price,
            'color' => $this->color,
            'is_active' => $this->is_active,
            'remarks' => $this->remarks ?? '',
        ];
    }
}
