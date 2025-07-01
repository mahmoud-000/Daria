<?php

namespace Modules\Setting\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Setting\Models\Setting;
use Modules\User\Models\User;

class SettingFactory extends Factory
{
    protected $model = Setting::class;

    public function definition()
    {
        $users = User::all()->pluck('id');
        return [
            'key' => $this->faker->sentence(3),
            'value' => $this->faker->word(),
            'user_id' => $users->random()
        ];
    }
}

