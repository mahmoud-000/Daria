<?php

namespace Modules\SaleReturn\Tests\Unit;

use App\Enums\ItemTypesEnum;
use App\Enums\ProductTypesEnum;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Modules\Item\Models\Item;
use Modules\Warehouse\Models\Warehouse;

class SaleReturnsTest extends TestCase
{
    use RefreshDatabase;
    public $standardItem;
    public $variantItem;
    public $saleReturn;

    public function setup(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
        $this->setupCompleted();

        $this->standardItem = $this->createInitItem();
        $this->variantItem = $this->createInitItem(ItemTypesEnum::VARIABLE, 'kg', 60, 90);
        $this->saleReturn = $this->createSaleReturn();
        $this->createOwner();
    }

    public function test_can_list_saleReturns()
    {
        $res = $this->get(route('api.saleReturns.index'))->json();
        $this->assertEquals(1, count($res['data']));
        $this->assertEquals(1, $res['meta']['total']);
    }

    public function test_can_not_create_saleReturn_without_required_inputs()
    {
        $res = $this->post(route('api.saleReturns.store'), [])
            ->assertStatus(422)
            ->withExceptions(collect(ValidationException::class))
            ->assertJsonValidationErrorFor('date', 'payload')
            ->assertJsonValidationErrorFor('warehouse_id', 'payload')
            ->assertJsonValidationErrorFor('customer_id', 'payload')
            ->assertJsonValidationErrorFor('details', 'payload')
            ->json();

        $this->assertEquals($res['payload']['details'][0], __('validation.custom.details.required'));
        $this->assertFalse($res['success']);
    }

    public function test_can_create_saleReturn_with_standard_item()
    {
        $tax_details = Item::getTaxDetails($this->standardItem);

        $detail_1 = $this->createDetail([
            'detailable_id' => null,
            'detailable_type' => null,
            'warehouse_id' => 1,
            'variant_id' => null,
            'item_id' => $this->standardItem->id,
            'quantity' => 35,
            'unit_id' => $this->standardItem->unit_id,
            'product_type' => $this->standardItem->product_type,

'type' => $this->standardItem->type,
            'production_date' => null,
            'expired_date' => null,
        ])->toArray();

        $pipelineId = $this->createPipeline()->id;
        
        $payment_1 = $this->createPayment([
            'paymentable_id' => null,
            'paymentable_type' => null,
            'type' => 1,
            'received_amount' => 100,
            'amount' => 50,
        ])->toArray();

        $res = $this->post(route('api.saleReturns.store'), [
            'date' => date('Y-m-d'),
            'warehouse_id' => 1,
            'customer_id' => $this->createCustomer()->id,
            'delegate_id' => $this->createDelegate()->id,
            'discount_type' => 1,
            'discount' => 0,
            'commission_type' => 1,
            'shipping' => 0,
            'other_expenses' => 0,
            'pipeline_id' => $pipelineId,
            'stage_id' => $this->storeStage(['pipeline_id' => $pipelineId, 'complete' => 100])->id,
            'grand_total' => $tax_details['total_cost'] * 2,
            'tax' => 0,
            'tax_net' => 0,
            'paid_amount' => 0,
            'details' => [$detail_1],
            'payments' => [$payment_1]
        ])->json();
        
        $this->assertDatabaseCount('saleReturns', 2);
        $this->assertDatabaseHas('saleReturns', [
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

        $this->assertDatabaseCount('payments', 1);
        $this->assertDatabaseHas('payments', [
            'type' => 1,
            'received_amount' => 100,
            'amount' => 50,
        ]);
        $this->assertTrue($res['success']);
        $this->assertEquals($res['payload'], __('status.created', ['name' => sprintf('%07d', 2), 'module' => __('modules.saleReturn')]));
    }

    public function test_can_create_saleReturn_with_variant_item()
    {
        $tax_details = Item::getTaxDetails($this->variantItem, $this->variantItem->variants->first());

        $detail_1 = $this->createDetail([
            'detailable_id' => null,
            'detailable_type' => null,
            'warehouse_id' => 4,
            'variant_id' => $this->variantItem->variants->first()->id,
            'item_id' => $this->variantItem->id,
            'quantity' => 22,
            'unit_id' => $this->variantItem->unit_id,
            'product_type' => $this->variantItem->product_type,
'type' => $this->variantItem->type,
            'production_date' => null,
            'expired_date' => null,
        ])->toArray();

        $pipelineId = $this->createPipeline()->id;
        $res = $this->post(route('api.saleReturns.store'), [
            'date' => date('Y-m-d'),
            'warehouse_id' => 4,
            'customer_id' => $this->createCustomer()->id,
            'delegate_id' => $this->createDelegate()->id,
            'discount_type' => 1,
            'discount' => 0,
            'commission_type' => 1,
            'shipping' => 0,
            'other_expenses' => 0,
            'pipeline_id' => $pipelineId,
            'stage_id' => $this->storeStage(['pipeline_id' => $pipelineId, 'complete' => 100])->id,
            'grand_total' => $tax_details['total_cost'] * 2,
            'tax' => 0,
            'tax_net' => 0,
            'paid_amount' => 0,
            'details' => [$detail_1]
        ])->json();
            
        $this->assertDatabaseCount('saleReturns', 2);
        $this->assertDatabaseHas('saleReturns', [
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
        $this->assertEquals($res['payload'], __('status.created', ['name' => sprintf('%07d', 2), 'module' => __('modules.saleReturn')]));
    }

    public function test_can_edit_saleReturn_and_add_a_new_details()
    {
        $saleReturn = $this->saleReturn;
        $saleReturnId = $saleReturn->id;
        $detail_1 = $this->createDetail([
            'detailable_id' => null,
            'detailable_type' => null,
            'warehouse_id' => 4,
            'variant_id' => $this->variantItem->variants->first()->id,
            'item_id' => $this->variantItem->id,
            'quantity' => 22,
            'unit_id' => $this->variantItem->unit_id,
            'product_type' => $this->variantItem->product_type,
'type' => $this->variantItem->type,
            'production_date' => null,
            'expired_date' => null,
        ])->toArray();

        $pipelineId = $this->createPipeline()->id;

        $res = $this->put(
            route('api.saleReturns.update', ['saleReturn' => $saleReturnId]),
            [
                'date' => date('Y-m-d'),
                'warehouse_id' => 4,
                'customer_id' => $this->createCustomer()->id,
                'delegate_id' => $this->createDelegate()->id,
                'discount_type' => 1,
                'discount' => 0,
                'commission_type' => 1,
                'shipping' => 0,
                'other_expenses' => 0,
                'pipeline_id' => $pipelineId,
                'stage_id' => $this->storeStage(['pipeline_id' => $pipelineId, 'complete' => 100])->id,
                'grand_total' => 100,
                'tax' => 0,
                'tax_net' => 0,
                'paid_amount' => 0,
                'details' => [$detail_1],
                'deletedDetails' => []
            ]
        )->json();

        $this->assertDatabaseCount('saleReturns', 1);
        $this->assertDatabaseHas('saleReturns', [
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
        $this->assertEquals($res['payload'], __('status.updated', ['name' => sprintf('%07d', $saleReturnId), 'module' => __('modules.saleReturn')]));
    }

    public function test_can_edit_saleReturn_and_remove_a_old_detail()
    {
        $warehouseId = $this->createWarehouse()->id;
        
        $old_detail = $this->createDetail([
            'detailable_id' => null,
            'detailable_type' => null,
            'warehouse_id' => $warehouseId,
            'variant_id' => $this->variantItem->variants->first()->id,
            'item_id' => $this->variantItem->id,
            'quantity' => 11,
            'unit_id' => $this->variantItem->unit_id,
            'product_type' => $this->variantItem->product_type,
'type' => $this->variantItem->type,
            'production_date' => null,
            'expired_date' => null,
        ])->toArray();

        $pipelineId = $this->createPipeline()->id;
        $stageId = $this->storeStage(['pipeline_id' => $pipelineId, 'complete' => 100])->id;

        $saleReturn = $this->createSaleReturn(['warehouse_id' => $warehouseId, 'pipeline_id' => $pipelineId, 'stage_id' => $stageId,]);

        $this->createStock([
            'warehouse_id' => $warehouseId,
            'variant_id' => $this->variantItem->variants->first()->id,
            'item_id' => $this->variantItem->id,
            'quantity' => 55,
        ]);

        $saleReturn->details()->create($old_detail);

        $saleReturnId = $saleReturn->id;
        $detail_1 = $this->createDetail([
            'detailable_id' => null,
            'detailable_type' => null,
            'variant_id' => $this->variantItem->variants->first()->id,
            'item_id' => $this->variantItem->id,
            'quantity' => 22,
            'unit_id' => $this->variantItem->unit_id,
            'product_type' => $this->variantItem->product_type,
'type' => $this->variantItem->type,
            'production_date' => null,
            'expired_date' => null,
        ])->toArray();

        $pipelineId = $this->createPipeline()->id;

        $res = $this->put(
            route('api.saleReturns.update', ['saleReturn' => $saleReturnId]),
            [
                'date' => date('Y-m-d'),
                'warehouse_id' => $warehouseId,
                'customer_id' => $this->createCustomer()->id,
                'delegate_id' => $this->createDelegate()->id,
                'discount' => 0,
                'discount_type' => 1,
                'shipping' => 0,
                'commission_type' => 1,
                'other_expenses' => 0,
                'pipeline_id' => $pipelineId,
                'stage_id' => $stageId,
                'grand_total' => 100,
                'tax' => 0,
                'tax_net' => 0,
                'paid_amount' => 0,
                'details' => [$detail_1],
                'deletedDetails' => [$saleReturn->details->first()->toArray()]
            ]
        )->json();
        
        $this->assertDatabaseCount('saleReturns', 2);
        $this->assertDatabaseHas('saleReturns', [
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
            'quantity' => 66
        ]);
        $this->assertTrue($res['success']);
        $this->assertEquals($res['payload'], __('status.updated', ['name' => sprintf('%07d', $saleReturnId), 'module' => __('modules.saleReturn')]));
    }

    public function test_can_show_saleReturn()
    {
        $saleReturnId = $this->saleReturn->id;
        $res = $this->get(route('api.saleReturns.show', ['saleReturn' => $saleReturnId]))->json();
        $this->assertEquals(1, count($res));
        $this->assertEquals($saleReturnId, $res['data']['id']);
    }

    public function test_can_delete_saleReturn()
    {
        $saleReturnId = $this->saleReturn->id;
        $res = $this->delete(route('api.saleReturns.destroy', ['saleReturn' => $saleReturnId]))->json();
        $this->assertTrue($res['success']);
        $this->assertEquals($res['payload'], __('status.deleted', ['name' => sprintf('%07d', $saleReturnId), 'module' => __('modules.saleReturn')]));
    }
}
