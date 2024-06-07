<?php

namespace Modules\Company\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Branch\Transformers\BranchResource;
use Modules\Upload\Transformers\UploadResource;

class CompanyResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'is_active' => $this->is_active,
            'remarks' => $this->remarks ?? '',
            'branches' => BranchResource::collection($this->whenLoaded('branches')),
            'logo' => $this->whenLoaded('media') && $this->media_count ? (new UploadResource($this->getFirstMedia('companies')))->additional(['conversion' => 'logo'])
                : config('upload.default_image')
        ];
    }
}
