<?php

namespace Modules\PurchaseReturn\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Stock\Transformers\StockResource;
use Modules\Upload\Transformers\UploadResource;
use Modules\Variant\Transformers\VariantResource;

class PurchaseReturnResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'label' => $this->label,
            'purchase_return_desc' => $this->purchase_return_desc,
            'currency' => $this->currency,
            'cost' => $this->cost->getAmount(),
            'cost_format' => $this->cost->formatTo(app()->getLocale()),
            'price' => $this->price->getAmount(),
            'price_format' => $this->price->formatTo(app()->getLocale()),
            'category_id' => $this->whenLoaded('category')->id ?? null,
            'category' => $this->whenLoaded('category')->name ?? null,
            'brand_id' => $this->whenLoaded('brand')->id ?? null,
            'brand' => $this->whenLoaded('brand')->name ?? null,
            'barcode_type' => $this->barcode_type,
            'barcode' => $this->barcode,
            'unit_id' => $this->unit_id,
            'sale_unit_id' => $this->sale_unit_id,
            'purchase_return_unit_id' => $this->purchase_return_unit_id,
            'sale_unit' => $this->whenLoaded('saleUnit')->short_name ?? null,
            'purchase_return_unit' => $this->whenLoaded('purchase_returnUnit')->short_name ?? null,
            'tax_type' => $this->tax_type,
            'tax' => $this->tax,
            'purchase_return_type' => $this->purchase_return_type,
            'is_active' => $this->is_active,
            'stock_alert' => $this->stock_alert,
            'remarks' => $this->remarks ?? "",
            'variants' => VariantResource::collection($this->whenLoaded('variants')),
            'stock' => StockResource::collection($this->whenLoaded('stock')),
            'purchase_return_images' => $this->whenLoaded('media') && count($this->whenLoaded('media'))
                ? $this->getMedia('purchase_returns')->map(function ($media) {
                    return (new UploadResource($media))->additional(['conversion' => 'purchase_return_images']);
                }) : [],
            'active_image' => $this->whenLoaded('media') && count($this->whenLoaded('media')) ? (new UploadResource($this->getFirstMedia('purchase_returns')))->additional(['conversion' => 'purchase_return_images']) : config('upload.default_image')
        ];
    }
}
