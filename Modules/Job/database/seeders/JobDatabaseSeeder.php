<?php

namespace Modules\Job\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Job\Models\Job;

class JobDatabaseSeeder extends Seeder
{
    public function run()
    {
        Job::factory(50)->create();
    }
}
