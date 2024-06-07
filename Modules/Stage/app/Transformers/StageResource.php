<?php

namespace Modules\Stage\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class StageResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'pipeline_id' => $this->pipeline_id,
            'pipeline' => $this->whenLoaded('pipeline') ? $this->whenLoaded('pipeline') : null,
            'name' => $this->name,
            'complete' => $this->complete,
            'color' => $this->color,
            'is_default' => $this->is_default,
            'is_active' => $this->is_active,
            'remarks' => $this->remarks ?? '',
        ];
    }
}
