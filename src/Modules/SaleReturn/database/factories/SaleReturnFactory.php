<?php

namespace Modules\SaleReturn\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Delegate\Models\Delegate;
use Modules\Pipeline\Models\Pipeline;
use Modules\SaleReturn\Models\SaleReturn;
use Modules\Stage\Models\Stage;
use Modules\Customer\Models\Customer;
use Modules\Warehouse\Models\Warehouse;

class SaleReturnFactory extends Factory
{
    protected $model = SaleReturn::class;

    public function definition()
    {
        $pipelineId = Pipeline::factory()->create()->id;
        return [
            'date' => $this->faker->date(),
            'customer_id' => function () {
                return Customer::factory()->create()->id;
            },
            'warehouse_id' => function () {
                return Warehouse::factory()->create()->id;
            },
            'pipeline_id' => $pipelineId,
            'stage_id' => function () use ($pipelineId) {
                return Stage::factory()->create(['pipeline_id' => $pipelineId])->id;
            },
            'delegate_id' => function () {
                return Delegate::factory()->create()->id;
            },
            'tax' => 0,
            // 'tax_net' => 0,
            'discount' => 0,
            'shipping' => 0,
            'other_expenses' => 0,
            'paid_amount' => 0,
            'grand_total' => 0,
            'payment_status' => $this->faker->randomElement([1, 2, 3]),
            'effected'  => true,
            'remarks'  => $this->faker->paragraph(),
        ];
    }
}
