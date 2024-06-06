<?php

namespace Modules\Auth\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Upload\Transformers\UploadResource;

class AuthResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'is_owner' => $this->is_owner,
            'username' => $this->username,
            'email' => $this->email,
            'avatar' => $this->relationLoaded('media') && $this->media_count ? (new UploadResource($this->getFirstMedia('users')))->additional(['conversion' => 'avatar'])
                : config('upload.default_image'),
        ];
    }
}
