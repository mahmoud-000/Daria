<?php

namespace Modules\Contact\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class ContactResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'mobile' => $this->mobile,
            'phone' => $this->phone,
            'contactable_type' => $this->contactable_type,
            'contactable_id' => $this->contactable_id,
        ];
    }
}
