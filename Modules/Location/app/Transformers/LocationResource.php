<?php

namespace Modules\Location\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class LocationResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'country' => $this->country,
            'city' => $this->city,
            'state' => $this->state,
            'zip' => $this->zip,
            'first_address' => $this->first_address,
            'second_address' => $this->second_address,
            'locationable_type' => $this->locationable_type,
            'locationable_id' => $this->locationable_id,
        ];
    }
}
