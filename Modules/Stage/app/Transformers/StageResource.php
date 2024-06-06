<?php

namespace Modules\Stage\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class StageResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'complete' => $this->complete,
            'color' => $this->color,
            'default' => $this->default,
        ];
    }
}
