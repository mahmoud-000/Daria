<?php

namespace Modules\Company\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Company\Models\Company;
use Modules\Branch\Models\Branch;

class CompanyDatabaseSeeder extends Seeder
{
    public function run()
    {
        Company::factory(5)->create()->each(function($pipe) {
            Branch::factory()->create(['company_id' => $pipe->id, 'name' => 'New', 'complete' => 20, 'default' => true]);
            Branch::factory()->create(['company_id' => $pipe->id, 'name' => 'Pending', 'complete' => 50, 'default' => false]);
            Branch::factory()->create(['company_id' => $pipe->id, 'name' => 'Review', 'complete' => 80, 'default' => false]);
            Branch::factory()->create(['company_id' => $pipe->id, 'name' => 'Complete', 'complete' => 100, 'default' => true]);
        });
    }
}
