<?php

namespace Modules\Patch\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Patch\Models\Patch;

class PatchFactory extends Factory
{
    protected $model = Patch::class;

    public function definition()
    {
        return [
            'warehouse_id' => null,
            'item_id' => null,
            'variant_id' => null,
            // 'production_date' => null,
            // 'expired_date' => null,
            'quantity' => 0,
        ];
    }
}
