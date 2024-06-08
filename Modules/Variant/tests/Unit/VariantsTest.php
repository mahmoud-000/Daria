<?php

namespace Modules\Variant\Tests\Unit;

use App\Enums\ItemTypesEnum;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Item\Models\Item;
use Modules\Variant\Models\Variant;

class VariantsTest extends TestCase
{
    use RefreshDatabase;
    public $variant;
    public function setup(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
        $this->setupCompleted();
        $this->createUnit(['name' => 'Kilogram', 'short_name' => 'kg', 'operator' => '*', 'operator_value' => 1]);
        $this->variant = Variant::factory()->for(Item::factory()->create())->create();

        $this->createOwner();
    }

    public function test_can_list_variant()
    {
        $res = $this->get(route('api.variants.index'))->json();
        $this->assertEquals(1, count($res['data']));
        $this->assertEquals(1, $res['meta']['total']);
    }

    public function test_can_create_variant_with_required_inputs()
    {
        $itemId = $this->createItem(['type' => ItemTypesEnum::VARIABLE->value])->id;

        $res = $this->post(route('api.variants.store'), [
            'name' => 'testvariantname',
            'color' => '#000000',
            'code' => '12345678',
            'cost' => 33,
            'price' => 66,
            'item_id' => $itemId,
            'is_active' => true
        ])->json();

        $this->assertDatabaseCount('variants', 2);
        $this->assertDatabaseHas('variants', [
            'name' => 'testvariantname',
        ]);
        $this->assertTrue($res['success']);
        $this->assertEquals($res['payload'], __('status.created', ['name' => 'testvariantname', 'module' => __('modules.variant')]));
    }

    public function test_can_edit_variant()
    {
        $res = $this->put(
            route('api.variants.update', ['variant' => $this->variant]),
            [
                'name' => 'newvariantname',
                'color' => '#ffffff',
                'code' => '99999999',
                'cost' => 33,
                'price' => 66,
                'item_id' => $this->variant->item_id,
                'is_active' => true
            ]
        )->json();

        $this->assertDatabaseCount('variants', 1);
        $this->assertDatabaseHas('variants', [
            'name' => 'newvariantname'
        ]);
        $this->assertTrue($res['success']);
        $this->assertEquals($res['payload'], __('status.updated', ['name' => 'newvariantname', 'module' => __('modules.variant')]));
    }

    public function test_can_show_variant()
    {
        $variantId = $this->variant->id;
        $res = $this->get(route('api.variants.show', ['variant' => $variantId]))->json();
        $this->assertEquals(1, count($res));
        $this->assertEquals($variantId, $res['data']['id']);
    }

    public function test_can_delete_variant()
    {
        $res = $this->delete(route('api.variants.destroy', ['variant' => $this->variant->id]))->json();
        $this->assertTrue($res['success']);
    }

    public function test_can_not_create_variant_with_name_already_exists_in_same_variant()
    {
        $item = $this->createItem(['type' => ItemTypesEnum::VARIABLE->value]);
        $old_variant = Variant::factory()->create(['name' => 'Variant 1', 'item_id' => $item->id]);
        $new_variant = $this->createVariant(['name' => 'Variant 1', 'item_id' => $item->id])->toArray();

        $res = $this->post(route('api.variants.store'), [
            ...$new_variant
        ])
            ->assertStatus(422)
            ->withExceptions(collect(ValidationException::class))
            ->assertJsonValidationErrorFor('name', 'payload')
            ->json();

        $this->assertFalse($res['success']);
    }

    public function test_can_not_edit_variant_with_name_already_exists_in_same_item()
    {
        $item = $this->createItem(['type' => ItemTypesEnum::VARIABLE->value]);

        $oldVariantInDB = Variant::factory()->create(['name' => 'Old Variant', 'item_id' => $item->id]);
        $variantInDB = Variant::factory()->create(['name' => 'Variant 1', 'item_id' => $item->id]);
        $variant = $this->createVariant(['name' => 'Variant 1', 'item_id' => $item->id])->toArray();

        $res = $this->put(
            route('api.variants.update', ['variant' => $variantInDB]),
            [
                'name' => $oldVariantInDB->name,
                'item_id' => $item->id
            ]
        )
            ->assertStatus(422)
            ->withExceptions(collect(ValidationException::class))
            ->assertJsonValidationErrorFor('name', 'payload')
            ->json();

        $this->assertFalse($res['success']);
    }
}
