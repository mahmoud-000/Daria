<?php

namespace Modules\Job\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class JobResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'min_salary' => $this->min_salary,
            'max_salary' => $this->max_salary,
            'remarks' => $this->remarks,
            'is_active' => $this->is_active
        ];
    }
}
