<?php

namespace Modules\Region\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Upload\Transformers\UploadResource;

class RegionResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'remarks' => $this->remarks,
            'is_active' => $this->is_active,
            'logo' => $this->whenLoaded('media') && $this->media_count
                ? (new UploadResource($this->getFirstMedia('regions')))->additional(['conversion' => 'logo'])
                : config('upload.default_image')
        ];
    }
}
