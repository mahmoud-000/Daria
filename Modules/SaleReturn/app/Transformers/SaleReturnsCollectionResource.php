<?php

namespace Modules\SaleReturn\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SaleReturnsCollectionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'ref' => sprintf('%07d', $this->id),
            'date' => $this->date,
            'customer' => $this->whenLoaded('customer') ? $this->customer->fullname : null,
            'warehouse' => $this->whenLoaded('warehouse') ? $this->warehouse->name : null,
            'pipeline' => $this->whenLoaded('pipeline') ? $this->pipeline->name : null,
            'paid_amount' => floatval($this->paid_amount),
            'payment_status' => $this->payment_status,
            'grand_total' => floatval($this->grand_total),
            // 'due' => floatval($this->grand_total) - floatval($this->whenLoaded('payments') ? $this->payments->sum('amount') : 0),
            'created_at' => $this->created_at->diffForHumans(),
            'updated_at' => $this->updated_at->diffForHumans(),
        ];
    }
}
