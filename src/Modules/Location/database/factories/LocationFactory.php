<?php

namespace Modules\Location\Database\factories;

use Faker\Provider\en_US\Address;
use Illuminate\Database\Eloquent\Factories\Factory;

class LocationFactory extends Factory
{
    protected $model = \Modules\Location\Models\Location::class;
    
    public function definition()
    {
        return [
            'name' => $this->faker->unique()->sentence(2),
            'country' => Address::country(),
            'city' => Address::cityPrefix(),
            'state' => Address::state(),
            'zip' => Address::postcode(),
            'first_address' => $this->faker->address(),
            'second_address' => $this->faker->streetAddress()
        ];
    }
}

