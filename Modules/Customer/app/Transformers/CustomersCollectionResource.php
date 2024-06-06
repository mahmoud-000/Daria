<?php

namespace Modules\Customer\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Upload\Transformers\UploadResource;

class CustomersCollectionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'fullname' => $this->fullname,
            'company_name' => $this->company_name,
            'is_active' => $this->is_active,
            'avatar' => $this->whenLoaded('media') && $this->media_count ? (new UploadResource($this->getFirstMedia('customers')))->additional(['conversion' => 'avatar'])
                : config('upload.default_image'),
            'created_at' => $this->created_at->diffForHumans(),
            'updated_at' => $this->updated_at->diffForHumans(),
        ];
    }
}
