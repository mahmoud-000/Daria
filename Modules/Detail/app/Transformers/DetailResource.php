<?php

namespace Modules\Detail\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Patch\Transformers\PatchResource;

class DetailResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'movement' => $this->movement,
            'type' => $this->type,
            'product_type' => $this->product_type,
            'production_date' => $this->production_date,
            'expired_date' => $this->expired_date,
            'name' => $this->item->name,
            'code' => $this->item->code,
            'is_available_for_edit_in_purchase' => $this->item->is_available_for_edit_in_purchase,
            'is_available_for_edit_in_sale' => $this->item->is_available_for_edit_in_sale,
            'variant' => $this->variant?->name,
            'unit_id' => $this->unit_id,
            'amount' => $this->amount,
            'tax' => $this->tax,
            'tax_type' => $this->tax_type,
            'discount' => $this->discount,
            'discount_type' => $this->discount_type,
            'detailable_id' => $this->detailable_id,
            'detailable_type' => $this->detailable_type,
            'warehouse_id' => $this->warehouse_id,
            'item_id' => $this->item_id,
            'variant_id' => $this->variant_id,
            'patch_id' => $this->patch_id,
            'total' => $this->total,
            'quantity' => $this->quantity,
            'stock' => $this->whenLoaded('stock') ? $this->stock->quantity : 0,
    }
}
