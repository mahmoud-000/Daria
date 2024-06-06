<?php

namespace Modules\Department\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Upload\Transformers\UploadResource;

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
            'is_active' => $this->is_active,
            'logo' => $this->whenLoaded('media') && $this->media_count
                ? (new UploadResource($this->getFirstMedia('departments')))->additional(['conversion' => 'logo'])
                : config('upload.default_image'),
        ];
    }
}
