<?php

namespace Modules\ِAccountType\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\ِAccountType\Models\ِAccountType;

class ِAccountTypeDatabaseSeeder extends Seeder
{
    public function run()
    {
        ِAccountType::factory(2)->create();
    }
}
