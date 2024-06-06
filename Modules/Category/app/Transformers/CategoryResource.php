<?php

namespace Modules\Category\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Upload\Transformers\UploadResource;

class CategoryResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'category_id' => $this->category_id,
            'category' => $this->whenLoaded('parent') ? CategoryResource::make($this->whenLoaded('parent')): null,
            'remarks' => $this->remarks ?? '',
            'is_active' => $this->is_active,
            'logo' => $this->whenLoaded('media') && $this->media_count
                ? (new UploadResource($this->getFirstMedia('categories')))->additional(['conversion' => 'logo'])
                : config('upload.default_image'),
        ];
    }
}
