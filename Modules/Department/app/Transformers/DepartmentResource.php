<?php

namespace Modules\Department\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Branch\Transformers\BranchResource;
use Modules\Company\Transformers\CompanyResource;

class DepartmentResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'department_id' => $this->department_id,
            'department' => $this->whenLoaded('parent') ? DepartmentResource::make($this->whenLoaded('parent')): null,
            'company_id' => $this->company_id,
            'company' => $this->whenLoaded('company') ? CompanyResource::make($this->whenLoaded('company')): null,
            'branch_id' => $this->branch_id,
            'branch' => $this->whenLoaded('branch') ? BranchResource::make($this->whenLoaded('branch')): null,
            'remarks' => $this->remarks ?? '',
            'is_active' => $this->is_active
        ];
    }
}
