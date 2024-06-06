<?php

namespace Modules\Location\Database\factories;

use Faker\Provider\en_US\Address;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\User\Models\User;

class LocationFactory extends Factory
{
    protected $model = \Modules\Location\Models\Location::class;
    
    public function definition()
    {
        $users = User::all()->pluck('id');
        return [
            'name' => $this->faker->unique()->randomElement(['Home', 'Work']),
            'country' => Address::country(),
            'city' => Address::cityPrefix(),
            'state' => Address::state(),
            'zip' => Address::postcode(),
            'first_address' => $this->faker->address(),
            'second_address' => $this->faker->streetAddress(),
            'locationable_type' => 'User',
            'locationable_id' => $users->random()
        ];
    }
}

