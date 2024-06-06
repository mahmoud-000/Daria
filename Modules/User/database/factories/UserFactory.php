<?php

namespace Modules\User\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\User\Models\User;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'username' => $this->faker->userName(),
            'firstname' => $this->faker->firstName(),
            'lastname' => $this->faker->lastName(),
            'email' => $this->faker->safeEmail(),

            // 'email_verified_at' => now(),
            'password' => 'Passwordsecret1@',
            'remember_token' => Str::random(10),

            'is_active' => $this->faker->boolean(),
            'send_notify' => $this->faker->boolean(),
            'gender' => $this->faker->randomElement([1, 2]),
            'remarks'  => $this->faker->paragraph(),
        ];
    }

    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
