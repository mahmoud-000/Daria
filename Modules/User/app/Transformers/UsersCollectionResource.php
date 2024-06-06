<?php

namespace Modules\User\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Upload\Transformers\UploadResource;

class UsersCollectionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'username' => $this->username,
            'fullname' => $this->firstname . ' ' . $this->lastname,
            'is_active' => $this->is_active,
            'avatar' => $this->whenLoaded('media') && $this->media_count ? (new UploadResource($this->getFirstMedia('users')))->additional(['conversion' => 'avatar'])
                : config('upload.default_image'),
            'created_at' => $this->created_at->diffForHumans(),
            'updated_at' => $this->updated_at->diffForHumans(),
        ];
    }
}
