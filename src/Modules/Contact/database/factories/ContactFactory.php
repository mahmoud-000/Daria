<?php

namespace Modules\Contact\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ContactFactory extends Factory
{
    protected $model = \Modules\Contact\Models\Contact::class;

    public function definition()
    {
        return [
            'name' => $this->faker->unique()->sentence(2),
            'email' => $this->faker->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'mobile' => $this->faker->e164PhoneNumber(),
        ];
    }
}

