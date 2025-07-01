<?php

namespace Modules\Patch\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class PatchResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'production_date' => $this->production_date ?? date('Y-m-d'),
            'expired_date' => $this->expired_date ?? date('Y-m-d'),
            
            'stock_id' => $this->stock_id,
            'item_id' => $this->item_id,
            'variant_id' => $this->variant_id,
            'warehouse_id' => $this->warehouse_id,
            'quantity' => $this->quantity,
            'amount' => $this->amount,
        ];
    }
}
