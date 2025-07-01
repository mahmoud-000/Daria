<?php

namespace Modules\Stock\Transformers;

use App\Enums\ProductTypesEnum;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Item\Models\Item;
use Modules\Item\Transformers\ItemResource;
use Modules\Variant\Transformers\VariantResource;
use Modules\Patch\Transformers\PatchResource;

class StockResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'sku' => $this->sku,
            'production_date' => $this->item->product_type === ProductTypesEnum::CONSUMER_ITEM ? date('Y-m-d') : null,
            'expired_date' => $this->item->product_type === ProductTypesEnum::CONSUMER_ITEM ? date('Y-m-d') : null,

            'item_id' => $this->item_id,
            'variant_id' => $this->variant_id,
            'warehouse_id' => $this->warehouse_id,
            'stock' => $this->quantity,

            'item' => ItemResource::make($this->whenLoaded('item')),
            'variant' => VariantResource::make($this->whenLoaded('variant')),

            'patches' => PatchResource::collection($this->whenLoaded('patches')),

            'tax_details' => Item::getTaxDetails($this->whenLoaded('item'), $this->whenLoaded('variant'))
        ];
    }
}
