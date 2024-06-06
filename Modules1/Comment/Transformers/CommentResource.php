<?php

namespace Modules\Comment\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Customer\Transformers\CustomerResource;
use Modules\User\Transformers\UserResource;

class CommentResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'text' => $this->text,
            'commentable_type' => $this->commentable_type,
            'commentable_id' => $this->commentable_id,
            'userable_type' => $this->userable_type,
            'userable_id' => $this->userable_id,
            'created_at' => $this->created_at->diffForHumans(),
            'user' => $this->userable_type === 'User' ? UserResource::make($this->userable) : CustomerResource::make($this->userable)
        ];
    }
}
