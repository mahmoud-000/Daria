<?php

namespace Modules\Item\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Upload\Transformers\UploadResource;
use Modules\Variant\Transformers\VariantResource;

class ItemResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'label' => $this->label,
            'item_desc' => $this->item_desc,
            'type' => $this->type,
            'product_type' => $this->product_type,
            'cost' => $this->cost,
            // 'cost_format' => $this->cost->formatTo(app()->getLocale()),
            'price' => $this->price,
            // 'price_format' => $this->price->formatTo(app()->getLocale()),
            'category_id' => $this->category_id,
            'category' => $this->whenLoaded('category')->name ?? null,
            'brand_id' => $this->brand_id,
            'brand' => $this->whenLoaded('brand')->name ?? null,
            'barcode_type' => $this->barcode_type,
            'code' => $this->code,
            'unit_id' => $this->unit_id,
            'sale_unit_id' => $this->sale_unit_id,
            'purchase_unit_id' => $this->purchase_unit_id,
            'sale_unit' => $this->whenLoaded('saleUnit'),
            'purchase_unit' => $this->whenLoaded('purchaseUnit'),
            'tax_type' => $this->tax_type,
            'tax' => $this->tax,
            'is_active' => $this->is_active,
            'is_available_for_purchase' => $this->is_available_for_purchase,
            'is_available_for_sale' => $this->is_available_for_sale,
            'is_available_for_edit_in_purchase' => $this->is_available_for_edit_in_purchase,
            'is_available_for_edit_in_sale' => $this->is_available_for_edit_in_sale,
            'stock_alert' => $this->stock_alert,
            'remarks' => $this->remarks ?? "",
            'variants' => VariantResource::collection($this->whenLoaded('variants')),
            
            'item_images' => $this->whenLoaded('media') && $this->media_count
                ? $this->getMedia('items')->map(function ($media) {
                    return (new UploadResource($media))->additional(['conversion' => 'item_images']);
                }) : [],
            'active_image' => $this->whenLoaded('media') && $this->media_count ? (new UploadResource($this->getFirstMedia('items')))->additional(['conversion' => 'item_images']) : config('upload.default_image'),
            'created_at' => $this->created_at->diffForHumans(),
            'updated_at' => $this->updated_at->diffForHumans()
        ];
    }
}
