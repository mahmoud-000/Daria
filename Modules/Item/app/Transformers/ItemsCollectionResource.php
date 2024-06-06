<?php

namespace Modules\Item\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemsCollectionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'cost' => $this->cost,
            'price' => $this->price,
            'unit' => $this->unit?->short_name,
            'variants' => $this?->variants,
            'quantity' => $this?->stock->sum('quantity'),
            'name' => $this->name,
            'type' => $this->type,
            'category' => $this->whenLoaded('category') ? $this->category->name : null,
            'brand' => $this->whenLoaded('brand') ? $this->brand->name : null,
            'is_active' => $this->is_active,
            'is_available_for_purchase' => $this->is_available_for_purchase,
            'is_available_for_sale' => $this->is_available_for_sale,
            'active_image' => $this->whenLoaded('media') && $this->media_count ? (new UploadResource($this->getFirstMedia('items')))->additional(['conversion' => 'item_images']) : config('upload.default_image'),
            'created_at' => $this->created_at->diffForHumans(),
            'updated_at' => $this->updated_at->diffForHumans()
        ];
    }
}
