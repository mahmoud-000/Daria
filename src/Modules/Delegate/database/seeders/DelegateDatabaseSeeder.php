<?php

namespace Modules\Delegate\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Permission\Models\Permission;
use Modules\Delegate\Models\Delegate;

class DelegateDatabaseSeeder extends Seeder
{
    public function run()
    {
        Delegate::factory(3)->create();
    }
}
