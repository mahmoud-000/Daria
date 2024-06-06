<?php

namespace Modules\User\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Contact\Transformers\ContactResource;
use Modules\Location\Transformers\LocationResource;
use Modules\Upload\Transformers\UploadResource;

class UserResource extends JsonResource
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
            'remarks' => $this->remarks ?? "",
            'gender' => $this->gender,
            'is_active' => $this->is_active,
            'send_notify' => $this->send_notify,
            'is_owner' => $this->is_owner,
            'locations' => LocationResource::collection($this->whenLoaded('locations')),
            'contacts' => ContactResource::collection($this->whenLoaded('contacts')),
            'avatar' => $this->whenLoaded('media') && $this->media_count ? (new UploadResource($this->getFirstMedia('users')))->additional(['conversion' => 'avatar'])
                : config('upload.default_image'),
            'roles' => ($this->relationLoaded('roles') && count($this->whenLoaded('roles'))) ? $this->roles->pluck('id') : [],
            'permissions' => $this->relationLoaded('permissions') ? $this->permissions->pluck('name') : [],
            'created_at' => $this->created_at->diffForHumans(),
            'updated_at' => $this->updated_at->diffForHumans(),
        ];
    }
}
