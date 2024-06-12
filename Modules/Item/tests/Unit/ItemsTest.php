<?php

namespace Modules\Item\Tests\Unit;

use App\Enums\ItemTypesEnum;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Modules\Variant\Models\Variant;

class ItemsTest extends TestCase
{
    use RefreshDatabase;

    public $item;
    public $unit;

    public function setup(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
        $this->setupCompleted();
        $this->unit = $this->createUnit(['name' => 'Kilogram', 'short_name' => 'kg', 'operator' => '*', 'operator_value' => 1]);
        $this->item = $this->createItem(['unit_id' => $this->unit->id]);
        $this->createOwner();
    }

    public function test_can_list_items()
    {
        $res = $this->get(route('api.items.index'))->json();
        $this->assertEquals(1, count($res['data']));
        $this->assertEquals(1, $res['meta']['total']);
    }

    public function test_can_create_standard_item()
    {
        $categoryId = $this->createCategory()->id;
        $res = $this->post(route('api.items.store'), [
            'name' => 'testitemname',
            'label' => 'testitemlabel',
            'barcode_type' => 1,
            'code' => '123123123',
            'sku' => 'it-23223',
            'cost' => 10,
            'price' => 20,
            'category_id' => $categoryId,
            'tax_type' => 1,
            'is_active' => 1,
            'is_available_for_purchase' => 1,
            'is_available_for_sale' => true,
            'is_available_for_edit_in_purchase' => 1,
            'is_available_for_edit_in_sale' => true,
            'product_type' => 1,
            'type' => ItemTypesEnum::STANDARD->value,
        ])
            ->json();

        $this->assertDatabaseCount('items', 2);
        $this->assertDatabaseHas('items', [
            'name' => 'testitemname',
            'label' => 'testitemlabel',
            'cost' => 10,
            'price' => 20,
            'category_id' => $categoryId,
            'tax_type' => 1,
            'type' => ItemTypesEnum::STANDARD->value
        ]);
        $this->assertTrue($res['success']);
        $this->assertDatabaseHas('items', ['name' => 'testitemname']);
        $this->assertEquals($res['payload'], __('status.created', ['name' => 'testitemname', 'module' => __('modules.item')]));
    }

    public function test_can_create_service_item()
    {
        $categoryId = $this->createCategory()->id;
        $res = $this->post(route('api.items.store'), [
            'name' => 'testitemname',
            'label' => 'testitemlabel',
            'barcode_type' => 1,
            'code' => '13313322',
            'sku' => 'it-23223',
            'price' => 20,
            'category_id' => $categoryId,
            'tax_type' => 1,
            'is_active' => 1,
            'is_available_for_purchase' => true,
            'is_available_for_sale' => 0,
            'is_available_for_edit_in_purchase' => 1,
            'is_available_for_edit_in_sale' => true,
            'product_type' => 1,
            'type' => ItemTypesEnum::SERVICE->value,
        ])
            ->json();

        $this->assertDatabaseCount('items', 2);
        $this->assertDatabaseHas('items', [
            'name' => 'testitemname',
            'label' => 'testitemlabel',
            'price' => 20,
            'category_id' => $categoryId,
            'tax_type' => 1,
            'type' => ItemTypesEnum::SERVICE->value
        ]);
        $this->assertTrue($res['success']);
        $this->assertDatabaseHas('items', ['name' => 'testitemname']);
        $this->assertEquals($res['payload'], __('status.created', ['name' => 'testitemname', 'module' => __('modules.item')]));
    }

    public function test_can_create_variable_item()
    {
        $categoryId = $this->createCategory()->id;
        $variant_1 = $this->createVariant(['name' => 'variant 1', 'code' => '23232323', 'sku' => 'it-23223'])->toArray();

        $res = $this->post(route('api.items.store'), [
            'name' => 'testitemname',
            'type' => ItemTypesEnum::VARIABLE->value,
            'label' => 'testitemlabel',
            'barcode_type' => 1,
            'code' => '1122331122',
            'sku' => '1122331122',
            'category_id' => $categoryId,
            'tax_type' => 1,
            'is_active' => 1,
            'is_available_for_purchase' => 1,
            'is_available_for_sale' => 1,
            'is_available_for_edit_in_purchase' => 1,
            'is_available_for_edit_in_sale' => true,
            'product_type' => 1,
            'variants' => [$variant_1]
        ])
            ->json();

        $this->assertDatabaseCount('items', 2);
        $this->assertDatabaseHas('items', [
            'name' => 'testitemname',
            'type' => ItemTypesEnum::VARIABLE->value,
            'label' => 'testitemlabel',
            'category_id' => $categoryId,
            'tax_type' => 1
        ]);

        $this->assertDatabaseCount('variants', 1);
        $this->assertDatabaseHas('variants', ['name' => 'variant 1']);

        $this->assertDatabaseCount('variants', 1);
        $this->assertDatabaseHas('items', ['name' => 'testitemname']);
        $this->assertTrue($res['success']);
        $this->assertEquals($res['payload'], __('status.created', ['name' => 'testitemname', 'module' => __('modules.item')]));
    }

    public function test_can_edit_standrd_item()
    {
        $categoryId = $this->createCategory()->id;
        $item = $this->createItem(['type' => ItemTypesEnum::STANDARD->value]);

        $itemId = $item->id;

        $res = $this->put(
            route('api.items.update', ['item' => $itemId]),
            [
                'name' => 'newitemname',
                'label' => 'testitemlabel',
                'code' => '99889988',
                'sku' => '99889988',
                'barcode_type' => 3,
                'category_id' => $categoryId,
                'tax_type' => 1,
                'is_active' => 1,
                'is_available_for_sale' => 1,
                'is_available_for_purchase' => 1,
                'is_available_for_edit_in_purchase' => 1,
                'is_available_for_edit_in_sale' => true,
                'product_type' => 1,
                'cost' => 15,
                'price' => 25,
                'type' => ItemTypesEnum::STANDARD->value,
            ]
        )->json();
     
        $this->assertDatabaseCount('items', 2);
        $this->assertTrue($res['success']);
        $this->assertEquals($item['id'], $itemId);
        $this->assertEquals($res['payload'], __('status.updated', ['name' => 'newitemname', 'module' => __('modules.item')]));
    }

    public function test_can_edit_variable_item()
    {
        $categoryId = $this->createCategory()->id;
        $item = $this->createItem(['type' => ItemTypesEnum::VARIABLE->value]);

        $itemId = $item->id;

        $variant_1 = $this->createVariant(['code' => '12121212', 'sku' => 'it-23223', 'item_id' => $itemId])->toArray();

        $res = $this->put(
            route('api.items.update', ['item' => $itemId]),
            [
                'name' => 'newitemname',
                'label' => 'testitemlabel',
                'code' => '55667788',
                'sku' => 'it-23223',
                'barcode_type' => 1,
                'category_id' => $categoryId,
                'tax_type' => 1,
                'is_active' => 1,
                'is_available_for_sale' => 1,
                'is_available_for_purchase' => 0,
                'is_available_for_edit_in_purchase' => 1,
                'is_available_for_edit_in_sale' => true,
                'product_type' => 1,
                'type' => ItemTypesEnum::VARIABLE->value,
                'variants' => [$variant_1]
            ]
        )->json();
        
        $this->assertDatabaseCount('items', 2);
        $this->assertDatabaseCount('variants', 1);
        $this->assertTrue($res['success']);
        $this->assertEquals($item['id'], $itemId);
        $this->assertEquals($res['payload'], __('status.updated', ['name' => 'newitemname', 'module' => __('modules.item')]));
    }

    public function test_can_edit_service_item()
    {
        $categoryId = $this->createCategory()->id;
        $item = $this->createItem(['type' => ItemTypesEnum::SERVICE->value]);

        $itemId = $item->id;

        $res = $this->put(
            route('api.items.update', ['item' => $itemId]),
            [
                'name' => 'newitemname',
                'label' => 'testitemlabel',
                'sku' => 'test-1222',
                'code' => '55666781',
                'barcode_type' => 1,
                'category_id' => $categoryId,
                'tax_type' => 1,
                'is_active' => 0,
                'is_available_for_sale' => 0,
                'is_available_for_purchase' => 1,
                'is_available_for_edit_in_purchase' => 1,
                'is_available_for_edit_in_sale' => true,
                'product_type' => 1,
                'price' => 120,
                'type' => ItemTypesEnum::SERVICE->value,
            ]
        )->json();

        $this->assertDatabaseCount('items', 2);
        $this->assertTrue($res['success']);
        $this->assertEquals($item['id'], $itemId);
        $this->assertEquals($res['payload'], __('status.updated', ['name' => 'newitemname', 'module' => __('modules.item')]));
    }

    public function test_can_show_item()
    {
        $itemId = $this->item->id;
        $res = $this->get(route('api.items.show', ['item' => $itemId]))->json();
        $this->assertEquals(1, count($res));
        $this->assertEquals($itemId, $res['data']['id']);
    }

    public function test_can_delete_item()
    {
        $res = $this->delete(route('api.items.destroy', ['item' => $this->item->id]))->json();
        $this->assertTrue($res['success']);
    }

    public function test_can_not_create_standard_item_without_required_inputs()
    {
        $res = $this->post(route('api.items.store'), [
            'name' => 'testitemname',
            ItemTypesEnum::STANDARD->value,
        ])
            ->assertStatus(422)
            ->withExceptions(collect(ValidationException::class))
            ->assertJsonValidationErrorFor('label', 'payload')
            ->assertJsonValidationErrorFor('category_id', 'payload')
            ->assertJsonValidationErrorFor('tax_type', 'payload')
            ->assertJsonValidationErrorFor('cost', 'payload')
            ->assertJsonValidationErrorFor('price', 'payload')
            ->json();

        $this->assertEquals($res['payload']['price'][0], __('validation.required', ['attribute' => 'price']));
        $this->assertFalse($res['success']);
    }

    public function test_can_not_create_service_item_without_required_inputs()
    {
        $res = $this->post(route('api.items.store'), [
            'name' => 'testitemname',
            ItemTypesEnum::SERVICE->value,
        ])
            ->assertStatus(422)
            ->withExceptions(collect(ValidationException::class))
            ->assertJsonValidationErrorFor('label', 'payload')
            ->assertJsonValidationErrorFor('category_id', 'payload')
            ->assertJsonValidationErrorFor('price', 'payload')
            ->assertJsonValidationErrorFor('tax_type', 'payload')
            ->json();

        $this->assertEquals($res['payload']['price'][0], __('validation.required', ['attribute' => 'price']));
        $this->assertFalse($res['success']);
    }

    public function test_can_not_create_variable_item_without_required_inputs()
    {
        $res = $this->post(route('api.items.store'), [
            'name' => 'testitemname',
            'type' => ItemTypesEnum::VARIABLE->value,
        ])
            ->assertStatus(422)
            ->withExceptions(collect(ValidationException::class))
            ->assertJsonValidationErrorFor('label', 'payload')
            ->assertJsonValidationErrorFor('category_id', 'payload')
            ->assertJsonValidationErrorFor('tax_type', 'payload')
            ->json();

        $this->assertFalse($res['success']);
    }

    public function test_can_not_create_variable_item_with_already_exist_variant_code()
    {
        $itemInDB = $this->createItem([
            'type' => ItemTypesEnum::VARIABLE->value
        ])->toArray();

        Variant::factory()->create(['code' => '123123123', 'item_id' => $itemInDB['id']]);

        $newVariant = $this->createVariant(['name' => 'variant 1', 'code' => '123123123', 'sku' => 'it-23223'])->toArray();

        $res = $this->post(route('api.items.store'), [
            'name' => 'testitemname',
            'type' => ItemTypesEnum::VARIABLE->value,
            'variants' => [$newVariant]
        ])
            ->assertStatus(422)
            ->withExceptions(collect(ValidationException::class))
            ->assertJsonValidationErrorFor('variants.0.code', 'payload')
            ->json();

        $this->assertFalse($res['success']);
    }

    public function test_can_not_edit_variable_item_if_variants_has_already_taken_code()
    {
        $categoryId = $this->createCategory()->id;
        $item = $this->createItem(['type' => ItemTypesEnum::VARIABLE->value]);
        $item2 = $this->createItem(['type' => ItemTypesEnum::VARIABLE->value]);
        $itemId = $item->id;

        $oldVariant = Variant::factory(['code' => '123123123', 'item_id' => $item2->id, 'sku' => 'var1-788',])->create();

        $newVariant = $this->createVariant(['code' => '123123123', 'sku' => 'sku-3123'])->toArray();

        $res = $this->put(
            route('api.items.update', ['item' => $itemId]),
            [
                'name' => 'newitemname',
                'type' => ItemTypesEnum::VARIABLE->value,
                'label' => 'testitemlabel',
                'code' => '55667788',
                'sku' => 'item-788',
                'barcode_type' => 1,
                'category_id' => $categoryId,
                'tax_type' => 1,
                'is_active' => 1,
                'is_available_for_sale' => 1,
                'is_available_for_purchase' => 0,
                'is_available_for_edit_in_purchase' => 1,
                'is_available_for_edit_in_sale' => true,
                'product_type' => 1,
                'variants' => [$newVariant]
            ]
        )
            ->assertStatus(422)
            ->withExceptions(collect(ValidationException::class))
            ->assertJsonValidationErrorFor('variants.0.code', 'payload')
            ->json();
    
        $this->assertFalse($res['success']);
    }
}
