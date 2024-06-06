<?php

namespace Modules\Department\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Department\Models\Department;

class DepartmentFactory extends Factory
{
    protected $model = Department::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'is_active' => true,
            'department_id' => null,
            'remarks'  => $this->faker->paragraph(),
        ];
    }
}
