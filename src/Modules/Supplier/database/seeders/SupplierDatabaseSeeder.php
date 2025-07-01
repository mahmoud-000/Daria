<?php

namespace Modules\Supplier\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Permission\Models\Permission;
use Modules\Supplier\Models\Supplier;

class SupplierDatabaseSeeder extends Seeder
{
    public function run()
    {
        Supplier::factory(30)->create();
    }
}
