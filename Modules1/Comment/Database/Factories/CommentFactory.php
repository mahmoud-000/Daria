<?php

namespace Modules\Comment\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\User\Entities\User;

class CommentFactory extends Factory
{
    protected $model = \Modules\Comment\Entities\Comment::class;

    public function definition()
    {
        $users = User::all()->pluck('id');
        return [
            'name' => $this->faker->unique()->randomElement(['Home', 'Work']),
            'email' => $this->faker->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'mobile' => $this->faker->e164PhoneNumber(),
            'commentable_type' => 'User',
            'commentable_id' => $users->random()
        ];
    }
}

