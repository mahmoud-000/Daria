<?php

namespace Modules\Branch\Database\Factories;

use Faker\Provider\en_US\Address;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Branch\Models\Branch;

class BranchFactory extends Factory
{
    protected $model = Branch::class;

    public function definition()
    {
        return [
            'name' => $this->faker->company(),
            'company_id' => null,
            'country' => Address::country(),
            'city' => Address::cityPrefix(),
            'state' => Address::state(),
            'zip' => Address::postcode(),
            'address' => $this->faker->address(),
            'email' => $this->faker->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'mobile' => $this->faker->e164PhoneNumber(),
            'is_main' => false,
            'is_active' => true,
        ];
    }
}
