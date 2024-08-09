<?php

namespace Modules\Department\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\User\Transformers\UsersCollectionResource;

class DepartmentResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'department_id' => $this->department_id,
            'parent' => $this->whenLoaded('parent') ? DepartmentResource::make($this->whenLoaded('parent')): null,
            'user_id' => $this->user_id,
            'manager' => $this->whenLoaded('manager') ? UsersCollectionResource::make($this->whenLoaded('manager')): null,
            'remarks' => $this->remarks ?? '',
            'is_active' => $this->is_active
        ];
    }
}
