<?php

namespace Modules\Payment\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Purchase\Models\Purchase;
use Modules\User\Models\User;

class PaymentFactory extends Factory
{
    protected $model = \Modules\Payment\Models\Payment::class;

    public function definition()
    {
        return [
            'date' => $this->faker->date(),
            'type' => 1,
            'received_amount' => 100,
            'amount' => 50,
            'note' => $this->faker->sentence(),
            'paymentable_type' => 'Putrchae',
            'paymentable_id' => null
        ];
    }
}
