<?php

namespace Modules\Pipeline\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Stage\Transformers\StageResource;
use Modules\Upload\Transformers\UploadResource;

class PipelineResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'module_name' => $this->module_name,
            'is_active' => $this->is_active,
            'remarks' => $this->remarks,
            'stages' => StageResource::collection($this->whenLoaded('stages')),
        ];
    }
}
