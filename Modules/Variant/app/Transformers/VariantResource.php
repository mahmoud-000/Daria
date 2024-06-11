<?php

namespace Modules\Variant\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Item\Transformers\ItemResource;
use Modules\Upload\Transformers\UploadResource;

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
            'sku' => $this->sku,
            'cost' => $this->cost,
            'price' => $this->price,
            'is_active' => $this->is_active,
            'remarks' => $this->remarks ?? '',
            'image' => $this->whenLoaded('media') && $this->media_count
                ? (new UploadResource($this->getFirstMedia('variants')))->additional(['conversion' => 'image'])
                : config('upload.default_image')
        ];
    }
}
