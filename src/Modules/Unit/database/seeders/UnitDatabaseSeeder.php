<?php

namespace Modules\Unit\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Unit\Models\Unit;

class UnitDatabaseSeeder extends Seeder
{
    public function run()
    {
        Unit::insert(
            [
                [
                    'name' => 'Kilogram',
                    'short_name' => 'kg',
                    'operator' => '*',
                    'operator_value' => 1,
                    'unit_id' => null,
                    'is_active' => true,
                ],
                [
                    'name' => 'Gram',
                    'short_name' => 'g',
                    'operator' => '/',
                    'operator_value' => 1000,
                    'unit_id' => 1,
                    'is_active' => true,
                ]
            ]
        );
    }
}
