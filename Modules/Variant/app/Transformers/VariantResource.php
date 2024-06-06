<?php

namespace Modules\Variant\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class VariantResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'cost' => $this->cost,
            'price' => $this->price,
            'color' => $this->color
        ];
    }
}
