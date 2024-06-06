<?php

namespace Modules\Customer\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Permission\Models\Permission;
use Modules\Customer\Models\Customer;

class CustomerDatabaseSeeder extends Seeder
{
    public function run()
    {
        Customer::factory(3)->create();
    }
}
