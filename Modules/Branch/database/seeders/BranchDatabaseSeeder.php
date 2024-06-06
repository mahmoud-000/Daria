<?php

namespace Modules\Branch\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Branch\Models\Branch;

class BranchDatabaseSeeder extends Seeder
{
    public function run()
    {
        Branch::factory(50)->create();
    }
}
