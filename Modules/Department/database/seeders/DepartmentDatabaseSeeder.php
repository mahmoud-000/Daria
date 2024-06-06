<?php

namespace Modules\Department\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Department\Models\Department;

class DepartmentDatabaseSeeder extends Seeder
{
    public function run()
    {
        Department::factory(50)->create();
    }
}
