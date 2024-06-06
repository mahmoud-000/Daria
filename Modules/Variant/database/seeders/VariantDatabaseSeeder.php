<?php

namespace Modules\Variant\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Variant\Models\Variant;

class VariantDatabaseSeeder extends Seeder
{
    public function run()
    {
        Variant::factory(50)->create();
    }
}
