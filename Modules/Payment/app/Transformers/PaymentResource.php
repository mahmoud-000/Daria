<?php

namespace Modules\Payment\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'note' => $this->note,
            'date' => $this->date,
            'time' => $this->time,
            'amount' => floatval($this->amount),
            'type' => $this->type,
            'ref' => $this->ref,
            'user_id' => $this->user_id,
            'paymentable_id' => $this->paymentable_id,
            'paymentable_type' => $this->paymentable_type,
        ];
    }
}
