<?php

namespace Modules\Contact\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\User\Models\User;

class ContactFactory extends Factory
{
    protected $model = \Modules\Contact\Models\Contact::class;

    public function definition()
    {
        $users = User::all()->pluck('id');
        return [
            'name' => $this->faker->unique()->randomElement(['Home', 'Work']),
            'email' => $this->faker->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'mobile' => $this->faker->e164PhoneNumber(),
            'contactable_type' => 'User',
            'contactable_id' => $users->random()
        ];
    }
}

