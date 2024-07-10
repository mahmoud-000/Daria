<?php

namespace Modules\Sale\Tests\Unit;

use App\Enums\ItemTypesEnum;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Modules\Item\Models\Item;

class SalesTest extends TestCase
{
    use RefreshDatabase;
    public $standardItem;
    public $variantItem;
    public $sale;

    public function setup(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
        $this->setupCompleted();

        $this->standardItem = $this->createInitItem();
        $this->variantItem = $this->createInitItem(ItemTypesEnum::VARIABLE, 'kg', 60, 90);
        $this->sale = $this->createSale();
        $this->createOwner();
    }

    public function test_can_list_sales()
    {
        $res = $this->get(route('api.sales.index'))->json();
        $this->assertEquals(1, count($res['data']));
        $this->assertEquals(1, $res['meta']['total']);
    }

    public function test_can_not_create_purchasse_without_required_inputs()
    {
        $res = $this->post(route('api.sales.store'), [])
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

    public function test_can_create_sale_with_standard_item()
    {
        $tax_details = Item::getTaxDetails($this->standardItem);

        $detail_1 = $this->createDetail([
            'detailable_id' => null,
            'detailable_type' => null,
            'warehouse_id' => 1,
            'variant_id' => null,
            'item_id' => $this->standardItem->id,
            'quantity' => 35,
            'unit_id' => $this->standardItem->sale_unit_id,
            'product_type' => $this->standardItem->product_type,
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

        $res = $this->post(route('api.sales.store'), [
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
        
        $this->assertDatabaseCount('sales', 2);
        $this->assertDatabaseHas('sales', [
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
            'quantity' => -35
        ]);

        $this->assertDatabaseCount('payments', 1);
        $this->assertDatabaseHas('payments', [
            'type' => 1,
            'received_amount' => 100,
            'amount' => 50,
        ]);
        $this->assertTrue($res['success']);
        $this->assertEquals($res['payload'], __('status.created', ['name' => sprintf('%07d', 2), 'module' => __('modules.sale')]));
    }

    public function test_can_create_sale_with_variant_item()
    {
        $tax_details = Item::getTaxDetails($this->variantItem, $this->variantItem->variants->first());

        $detail_1 = $this->createDetail([
            'detailable_id' => null,
            'detailable_type' => null,
            'warehouse_id' => 4,
            'variant_id' => $this->variantItem->variants->first()->id,
            'item_id' => $this->variantItem->id,
            'quantity' => 22,
            'unit_id' => $this->variantItem->sale_unit_id,
            'product_type' => $this->variantItem->product_type,
            'production_date' => null,
            'expired_date' => null,
        ])->toArray();

        $pipelineId = $this->createPipeline()->id;
        $res = $this->post(route('api.sales.store'), [
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
            
        $this->assertDatabaseCount('sales', 2);
        $this->assertDatabaseHas('sales', [
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
            'quantity' => -22
        ]);
        $this->assertTrue($res['success']);
        $this->assertEquals($res['payload'], __('status.created', ['name' => sprintf('%07d', 2), 'module' => __('modules.sale')]));
    }

    public function test_can_edit_sale_and_add_a_new_details()
    {
        $sale = $this->sale;
        $saleId = $sale->id;
        $detail_1 = $this->createDetail([
            'detailable_id' => null,
            'detailable_type' => null,
            'warehouse_id' => 4,
            'variant_id' => $this->variantItem->variants->first()->id,
            'item_id' => $this->variantItem->id,
            'quantity' => 22,
            'unit_id' => $this->variantItem->sale_unit_id,
            'product_type' => $this->variantItem->product_type,
            'production_date' => null,
            'expired_date' => null,
        ])->toArray();

        $pipelineId = $this->createPipeline()->id;

        $res = $this->put(
            route('api.sales.update', ['sale' => $saleId]),
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

        $this->assertDatabaseCount('sales', 1);
        $this->assertDatabaseHas('sales', [
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
            'quantity' => -22
        ]);
        $this->assertTrue($res['success']);
        $this->assertEquals($res['payload'], __('status.updated', ['name' => sprintf('%07d', $saleId), 'module' => __('modules.sale')]));
    }

    public function test_can_edit_sale_and_remove_a_old_detail()
    {
        $warehouseId = $this->createWarehouse()->id;
        
        $old_detail = $this->createDetail([
            'detailable_id' => null,
            'detailable_type' => null,
            'warehouse_id' => $warehouseId,
            'variant_id' => $this->variantItem->variants->first()->id,
            'item_id' => $this->variantItem->id,
            'quantity' => 11,
            'unit_id' => $this->variantItem->sale_unit_id,
            'product_type' => $this->variantItem->product_type,
            'production_date' => null,
            'expired_date' => null,
        ])->toArray();

        $pipelineId = $this->createPipeline()->id;
        $stageId = $this->storeStage(['pipeline_id' => $pipelineId, 'complete' => 100])->id;

        $sale = $this->createSale(['warehouse_id' => $warehouseId, 'pipeline_id' => $pipelineId, 'stage_id' => $stageId,]);

        $this->createStock([
            'warehouse_id' => $warehouseId,
            'variant_id' => $this->variantItem->variants->first()->id,
            'item_id' => $this->variantItem->id,
            'quantity' => 55,
        ]);

        $sale->details()->create($old_detail);

        $saleId = $sale->id;
        $detail_1 = $this->createDetail([
            'detailable_id' => null,
            'detailable_type' => null,
            'variant_id' => $this->variantItem->variants->first()->id,
            'item_id' => $this->variantItem->id,
            'quantity' => 22,
            'unit_id' => $this->variantItem->sale_unit_id,
            'product_type' => $this->variantItem->product_type,
            'production_date' => null,
            'expired_date' => null,
        ])->toArray();

        $pipelineId = $this->createPipeline()->id;

        $res = $this->put(
            route('api.sales.update', ['sale' => $saleId]),
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
                'deletedDetails' => [$sale->details->first()->toArray()]
            ]
        )->json();
        
        $this->assertDatabaseCount('sales', 2);
        $this->assertDatabaseHas('sales', [
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
        $this->assertEquals($res['payload'], __('status.updated', ['name' => sprintf('%07d', $saleId), 'module' => __('modules.sale')]));
    }

    public function test_can_show_sale()
    {
        $saleId = $this->sale->id;
        $res = $this->get(route('api.sales.show', ['sale' => $saleId]))->json();
        $this->assertEquals(1, count($res));
        $this->assertEquals($saleId, $res['data']['id']);
    }

    public function test_can_delete_sale()
    {
        $saleId = $this->sale->id;
        $res = $this->delete(route('api.sales.destroy', ['sale' => $saleId]))->json();
        $this->assertTrue($res['success']);
        $this->assertEquals($res['payload'], __('status.deleted', ['name' => sprintf('%07d', $saleId), 'module' => __('modules.sale')]));
    }
}
