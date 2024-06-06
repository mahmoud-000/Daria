<?php

namespace Modules\Stage\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Stage\Models\Stage;

class StageDatabaseSeeder extends Seeder
{
    public function run()
    {
        Stage::factory(50)->create();
    }
}
