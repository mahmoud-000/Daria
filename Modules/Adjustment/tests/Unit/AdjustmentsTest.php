<?php

namespace Modules\Adjustment\Tests\Unit;

use App\Enums\ItemTypesEnum;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Modules\Item\Models\Item;

class AdjustmentsTest extends TestCase
{
    use RefreshDatabase;
    public $standardItem;
    public $variantItem;
    public $adjustment;

    public function setup(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
        $this->setupCompleted();

        $this->standardItem = $this->createInitItem();
        $this->variantItem = $this->createInitItem(ItemTypesEnum::VARIABLE, 'kg', 60, 90);
        $this->adjustment = $this->createAdjustment();
        $this->createOwner();
    }

    public function test_can_list_adjustments()
    {
        $res = $this->get(route('api.adjustments.index'))->json();
        $this->assertEquals(1, count($res['data']));
        $this->assertEquals(1, $res['meta']['total']);
    }

    public function test_can_not_create_adjustment_without_required_inputs()
    {
        $res = $this->post(route('api.adjustments.store'), [])
            ->assertStatus(422)
            ->withExceptions(collect(ValidationException::class))
            ->assertJsonValidationErrorFor('date', 'payload')
            ->assertJsonValidationErrorFor('warehouse_id', 'payload')
            ->assertJsonValidationErrorFor('details', 'payload')
            ->json();

        $this->assertEquals($res['payload']['details'][0], __('validation.custom.details.required'));
        $this->assertFalse($res['success']);
    }

    public function test_can_create_adjustment_with_standard_item()
    {
        $detail_1 = $this->createDetail([
            'movement' => 1,
            'detailable_id' => null,
            'detailable_type' => null,
            'warehouse_id' => 1,
            'variant_id' => null,
            'item_id' => $this->standardItem->id,
            'quantity' => 35,
            'unit_id' => $this->standardItem->purchase_unit_id,
            'product_type' => $this->standardItem->product_type,

'type' => $this->standardItem->type,
            'production_date' => null,
            'expired_date' => null,
        ])->toArray();

        $pipelineId = $this->createPipeline()->id;
        
        $res = $this->post(route('api.adjustments.store'), [
            'date' => date('Y-m-d'),
            'warehouse_id' => 1,
            'pipeline_id' => $pipelineId,
            'stage_id' => $this->storeStage(['pipeline_id' => $pipelineId, 'complete' => 100])->id,
            'grand_total' => 100,
            'details' => [$detail_1],
        ])->json();
        
        $this->assertDatabaseCount('adjustments', 2);
        $this->assertDatabaseHas('adjustments', [
            'warehouse_id' => 1,
        ]);

        $this->assertDatabaseCount('details', 1);
        $this->assertDatabaseHas('details', [
            'item_id' => $this->standardItem->id,
            'variant_id' => null,
        ]);

        $this->assertDatabaseHas('stock', [
            'item_id' => $this->standardItem->id,
            'variant_id' => null,
            'quantity' => 35
        ]);

        $this->assertTrue($res['success']);
        $this->assertEquals($res['payload'], __('status.created', ['name' => sprintf('%07d', 2), 'module' => __('modules.adjustment')]));
    }

    public function test_can_create_adjustment_with_variant_item()
    {
        $detail_1 = $this->createDetail([
            'movement' => 1,
            'detailable_id' => null,
            'detailable_type' => null,
            'warehouse_id' => 4,
            'variant_id' => $this->variantItem->variants->first()->id,
            'item_id' => $this->variantItem->id,
            'quantity' => 22,
            'unit_id' => $this->variantItem->purchase_unit_id,
            'product_type' => $this->variantItem->product_type,
'type' => $this->variantItem->type,
            'production_date' => null,
            'expired_date' => null,
        ])->toArray();

        $pipelineId = $this->createPipeline()->id;
        $res = $this->post(route('api.adjustments.store'), [
            'date' => date('Y-m-d'),
            'warehouse_id' => 4,
            'pipeline_id' => $pipelineId,
            'stage_id' => $this->storeStage(['pipeline_id' => $pipelineId, 'complete' => 100])->id,
            'grand_total' => 200,
            'details' => [$detail_1]
        ])->json();
            
        $this->assertDatabaseCount('adjustments', 2);
        $this->assertDatabaseHas('adjustments', [
            'warehouse_id' => 4,
        ]);

        $this->assertDatabaseCount('details', 1);
        $this->assertDatabaseHas('details', [
            'item_id' => $this->variantItem->id,
            'variant_id' => $this->variantItem->variants->first()->id,
        ]);

        $this->assertDatabaseHas('stock', [
            'item_id' => $this->variantItem->id,
            'variant_id' => $this->variantItem->variants->first()->id,
            'quantity' => 22
        ]);
        $this->assertTrue($res['success']);
        $this->assertEquals($res['payload'], __('status.created', ['name' => sprintf('%07d', 2), 'module' => __('modules.adjustment')]));
    }

    public function test_can_edit_adjustment_and_add_a_new_details()
    {
        $adjustment = $this->adjustment;
        $adjustmentId = $adjustment->id;
        $detail_1 = $this->createDetail([
            'movement' => 1,
            'detailable_id' => null,
            'detailable_type' => null,
            'warehouse_id' => 4,
            'variant_id' => $this->variantItem->variants->first()->id,
            'item_id' => $this->variantItem->id,
            'quantity' => 22,
            'unit_id' => $this->variantItem->purchase_unit_id,
            'product_type' => $this->variantItem->product_type,
'type' => $this->variantItem->type,
            'production_date' => null,
            'expired_date' => null,
        ])->toArray();

        $pipelineId = $this->createPipeline()->id;

        $res = $this->put(
            route('api.adjustments.update', ['adjustment' => $adjustmentId]),
            [
                'date' => date('Y-m-d'),
                'warehouse_id' => 4,
                'pipeline_id' => $pipelineId,
                'stage_id' => $this->storeStage(['pipeline_id' => $pipelineId, 'complete' => 100])->id,
                'grand_total' => 100,   
                'details' => [$detail_1],
                'deletedDetails' => []
            ]
        )->json();

        $this->assertDatabaseCount('adjustments', 1);
        $this->assertDatabaseHas('adjustments', [
            'warehouse_id' => 4,
        ]);

        $this->assertDatabaseCount('details', 1);
        $this->assertDatabaseHas('details', [
            'item_id' => $this->variantItem->id,
            'variant_id' => $this->variantItem->variants->first()->id,
        ]);

        $this->assertDatabaseHas('stock', [
            'item_id' => $this->variantItem->id,
            'variant_id' => $this->variantItem->variants->first()->id,
            'quantity' => 22
        ]);
        $this->assertTrue($res['success']);
        $this->assertEquals($res['payload'], __('status.updated', ['name' => sprintf('%07d', $adjustmentId), 'module' => __('modules.adjustment')]));
    }

    public function test_can_edit_adjustment_and_remove_a_old_detail()
    {
        $warehouseId = $this->createWarehouse()->id;
        
        $old_detail = $this->createDetail([
            'movement' => 1,
            'detailable_id' => null,
            'detailable_type' => null,
            'warehouse_id' => $warehouseId,
            'variant_id' => $this->variantItem->variants->first()->id,
            'item_id' => $this->variantItem->id,
            'quantity' => 11,
            'unit_id' => $this->variantItem->purchase_unit_id,
            'product_type' => $this->variantItem->product_type,
'type' => $this->variantItem->type,
            'production_date' => null,
            'expired_date' => null,
        ])->toArray();

        $pipelineId = $this->createPipeline()->id;
        $stageId = $this->storeStage(['pipeline_id' => $pipelineId, 'complete' => 100])->id;

        $adjustment = $this->createAdjustment(['warehouse_id' => $warehouseId, 'pipeline_id' => $pipelineId, 'stage_id' => $stageId,]);

        $this->createStock([
            'warehouse_id' => $warehouseId,
            'variant_id' => $this->variantItem->variants->first()->id,
            'item_id' => $this->variantItem->id,
            'quantity' => 55,
        ]);

        $adjustment->details()->create($old_detail);

        $adjustmentId = $adjustment->id;
        $detail_1 = $this->createDetail([
            'movement' => 1,
            'detailable_id' => null,
            'detailable_type' => null,
            'variant_id' => $this->variantItem->variants->first()->id,
            'item_id' => $this->variantItem->id,
            'quantity' => 22,
            'unit_id' => $this->variantItem->purchase_unit_id,
            'product_type' => $this->variantItem->product_type,
'type' => $this->variantItem->type,
            'production_date' => null,
            'expired_date' => null,
        ])->toArray();

        $pipelineId = $this->createPipeline()->id;

        $res = $this->put(
            route('api.adjustments.update', ['adjustment' => $adjustmentId]),
            [
                'date' => date('Y-m-d'),
                'warehouse_id' => $warehouseId,
                'pipeline_id' => $pipelineId,
                'stage_id' => $stageId,
                'grand_total' => 100,
                'details' => [$detail_1],
                'deletedDetails' => [$adjustment->details->first()->toArray()]
            ]
        )->json();
        
        $this->assertDatabaseCount('adjustments', 2);
        $this->assertDatabaseHas('adjustments', [
            'warehouse_id' => $warehouseId,
        ]);

        $this->assertDatabaseCount('details', 1);
        $this->assertDatabaseHas('details', [
            'item_id' => $this->variantItem->id,
            'variant_id' => $this->variantItem->variants->first()->id,
        ]);

        $this->assertDatabaseHas('stock', [
            'item_id' => $this->variantItem->id,
            'variant_id' => $this->variantItem->variants->first()->id,
            'warehouse_id' => $warehouseId,
            'quantity' => 44
        ]);
        $this->assertTrue($res['success']);
        $this->assertEquals($res['payload'], __('status.updated', ['name' => sprintf('%07d', $adjustmentId), 'module' => __('modules.adjustment')]));
    }

    public function test_can_show_adjustment()
    {
        $adjustmentId = $this->adjustment->id;
        $res = $this->get(route('api.adjustments.show', ['adjustment' => $adjustmentId]))->json();
        $this->assertEquals(1, count($res));
        $this->assertEquals($adjustmentId, $res['data']['id']);
    }

    public function test_can_delete_adjustment()
    {
        $adjustmentId = $this->adjustment->id;
        $res = $this->delete(route('api.adjustments.destroy', ['adjustment' => $adjustmentId]))->json();
        $this->assertTrue($res['success']);
        $this->assertEquals($res['payload'], __('status.deleted', ['name' => sprintf('%07d', $adjustmentId), 'module' => __('modules.adjustment')]));
    }
}
