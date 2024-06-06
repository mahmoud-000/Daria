<?php

namespace Modules\Unit\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class UnitResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'short_name' => $this->short_name,
            'operator' => $this->operator,
            'operator_value' => $this->operator_value,
            'unit_id' => $this->unit_id,
            'remarks' => $this->remarks,
            'is_active' => $this->is_active,
            // 'sub' => $this->whenLoaded('sub'),
            // 'base_unit' => $this->whenLoaded('parent')?->name ?? null,
        ];
    }
}
