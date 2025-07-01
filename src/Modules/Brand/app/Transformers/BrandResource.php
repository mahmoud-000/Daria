<?php

namespace Modules\Brand\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Upload\Transformers\UploadResource;

class BrandResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'remarks' => $this->remarks,
            'is_active' => $this->is_active,
            'logo' => $this->whenLoaded('media') && $this->media_count
                ? (new UploadResource($this->getFirstMedia('brands')))->additional(['conversion' => 'logo'])
                : config('upload.default_image')
        ];
    }
}
