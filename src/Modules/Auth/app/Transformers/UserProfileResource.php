<?php

namespace Modules\Auth\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Contact\Transformers\ContactResource;
use Modules\Location\Transformers\LocationResource;
use Modules\Upload\Transformers\UploadResource;

class UserProfileResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'email' => $this->email,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'date_of_birth' => $this->date_of_birth,
            'date_of_joining' => $this->date_of_joining,
            'remarks' => $this->remarks,
            'gender' => $this->gender,
            'send_notify' => $this->send_notify,
            'locations' => LocationResource::collection($this->whenLoaded('locations')),
            'contacts' => ContactResource::collection($this->whenLoaded('contacts')),
            'avatar' => $this->relationLoaded('media') && $this->media_count ? (new UploadResource($this->getFirstMedia('users')))->additional(['conversion' => 'avatar'])
                : config('upload.default_image'),
        ];
    }
}
