<?php

namespace Modules\Branch\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Company\Transformers\CompanyResource;

class BranchResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'company_id' => $this->company_id,
            'company' => $this->whenLoaded('company') ? CompanyResource::make($this->whenLoaded('company')) : null,
            'name' => $this->name,
            'email' => $this->email,
            'address' => $this->address,
            'country' => $this->country,
            'city' => $this->city,
            'state' => $this->state,
            'zip' => $this->zip,
            'mobile' => $this->mobile,
            'phone' => $this->phone,
            'is_active' => $this->is_active,
            'is_main' => $this->is_main,
            'remarks' => $this->remarks ?? '',
        ];
    }
}
