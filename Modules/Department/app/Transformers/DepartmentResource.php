<?php

namespace Modules\Department\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class DepartmentResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'department_id' => $this->department_id,
            'department' => $this->whenLoaded('parent') ? DepartmentResource::make($this->whenLoaded('parent')): null,
            'remarks' => $this->remarks ?? '',
            'is_active' => $this->is_active
        ];
    }
}
