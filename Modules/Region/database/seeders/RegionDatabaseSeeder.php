<?php

namespace Modules\Region\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Region\Models\Region;

class RegionDatabaseSeeder extends Seeder
{
    public function run()
    {
        Region::factory(50)->create();
    }
}
