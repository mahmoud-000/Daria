<?php

namespace Modules\Quotation\Tests\Unit;

use App\Enums\ItemTypesEnum;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Modules\Item\Models\Item;

class QuotationsTest extends TestCase
{
    use RefreshDatabase;
    public $standardItem;
    public $variantItem;
    public $quotation;

    public function setup(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
        $this->setupCompleted();

        $this->standardItem = $this->createInitItem();
        $this->variantItem = $this->createInitItem(ItemTypesEnum::VARIABLE, 'kg', 60, 90);
        $this->quotation = $this->createQuotation();
        $this->createOwner();
    }

    public function test_can_list_quotations()
    {
        $res = $this->get(route('api.quotations.index'))->json();
        $this->assertEquals(1, count($res['data']));
        $this->assertEquals(1, $res['meta']['total']);
    }

    public function test_can_not_create_purchasse_without_required_inputs()
    {
        $res = $this->post(route('api.quotations.store'), [])
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

    public function test_can_create_quotation_with_standard_item()
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

        $res = $this->post(route('api.quotations.store'), [
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
            'details' => [$detail_1],
        ])->json();

        $this->assertDatabaseCount('quotations', 2);
        $this->assertDatabaseHas('quotations', [
            'warehouse_id' => 1,
        ]);

        $this->assertDatabaseCount('details', 1);
        $this->assertDatabaseHas('details', [
            'item_id' => $this->standardItem->id,
            'variant_id' => null,
        ]);

        $this->assertTrue($res['success']);
        $this->assertEquals($res['payload'], __('status.created', ['name' => sprintf('%07d', 2), 'module' => __('modules.quotation')]));
    }

    public function test_can_create_quotation_with_variant_item()
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
        $res = $this->post(route('api.quotations.store'), [
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
            'details' => [$detail_1]
        ])->json();

        $this->assertDatabaseCount('quotations', 2);
        $this->assertDatabaseHas('quotations', [
            'warehouse_id' => 4,
        ]);

        $this->assertDatabaseCount('details', 1);
        $this->assertDatabaseHas('details', [
            'item_id' => $this->variantItem->id,
            'variant_id' => $this->variantItem->variants->first()->id,
        ]);

        $this->assertTrue($res['success']);
        $this->assertEquals($res['payload'], __('status.created', ['name' => sprintf('%07d', 2), 'module' => __('modules.quotation')]));
    }

    public function test_can_edit_quotation_and_add_a_new_details()
    {
        $quotation = $this->quotation;
        $quotationId = $quotation->id;
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
            route('api.quotations.update', ['quotation' => $quotationId]),
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
                'details' => [$detail_1],
                'deletedDetails' => []
            ]
        )->json();

        $this->assertDatabaseCount('quotations', 1);
        $this->assertDatabaseHas('quotations', [
            'warehouse_id' => 4,
        ]);

        $this->assertDatabaseCount('details', 1);
        $this->assertDatabaseHas('details', [
            'item_id' => $this->variantItem->id,
            'variant_id' => $this->variantItem->variants->first()->id,
        ]);

        $this->assertTrue($res['success']);
        $this->assertEquals($res['payload'], __('status.updated', ['name' => sprintf('%07d', $quotationId), 'module' => __('modules.quotation')]));
    }

    public function test_can_edit_quotation_and_remove_a_old_detail()
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

        $quotation = $this->createQuotation(['warehouse_id' => $warehouseId, 'pipeline_id' => $pipelineId, 'stage_id' => $stageId,]);

        $quotation->details()->create($old_detail);

        $quotationId = $quotation->id;
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
            route('api.quotations.update', ['quotation' => $quotationId]),
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
                'details' => [$detail_1],
                'deletedDetails' => [$quotation->details->first()->toArray()]
            ]
        )->json();
      
        $this->assertDatabaseCount('quotations', 2);
        $this->assertDatabaseHas('quotations', [
            'warehouse_id' => $warehouseId,
        ]);

        $this->assertDatabaseCount('details', 1);
        $this->assertDatabaseHas('details', [
            'item_id' => $this->variantItem->id,
            'variant_id' => $this->variantItem->variants->first()->id,
        ]);

        $this->assertTrue($res['success']);
        $this->assertEquals($res['payload'], __('status.updated', ['name' => sprintf('%07d', $quotationId), 'module' => __('modules.quotation')]));
    }

    public function test_can_show_quotation()
    {
        $quotationId = $this->quotation->id;
        $res = $this->get(route('api.quotations.show', ['quotation' => $quotationId]))->json();
        $this->assertEquals(1, count($res));
        $this->assertEquals($quotationId, $res['data']['id']);
    }

    public function test_can_delete_quotation()
    {
        $quotationId = $this->quotation->id;
        $res = $this->delete(route('api.quotations.destroy', ['quotation' => $quotationId]))->json();
        $this->assertTrue($res['success']);
        $this->assertEquals($res['payload'], __('status.deleted', ['name' => sprintf('%07d', $quotationId), 'module' => __('modules.quotation')]));
    }
}
