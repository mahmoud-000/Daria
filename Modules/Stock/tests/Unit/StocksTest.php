<?php

namespace Modules\Stock\Tests\Unit;

use App\Enums\ItemTypesEnum;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StocksTest extends TestCase
{
    use RefreshDatabase;

    public function setup(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
        $this->setupCompleted();
        $this->createOwner();
    }

    public function test_can_list_stocks_by_warehouse_without_search_term()
    {
        $warehouse1Id = $this->createWarehouse()->id;
        $warehouse2Id = $this->createWarehouse()->id;
        $item_standard = $this->createInitItem(warehouse1Id: $warehouse1Id, warehouse2Id: $warehouse2Id);
        $item_variable = $this->createInitItem(ItemTypesEnum::VARIABLE, warehouse1Id: $warehouse1Id, warehouse2Id: $warehouse2Id);
        $item_service = $this->createInitItem(ItemTypesEnum::SERVICE, warehouse1Id: $warehouse1Id, warehouse2Id: $warehouse2Id);

        $res = $this->get(route('api.stock.by_warehouse', [
            'warehouse' => 1,
            'invoice_type' => 'purchase',
            'not_include' => [3]
        ]))->json();
        
        $this->assertDatabaseCount('stock', 6);
        $this->assertDatabaseHas('stock', [
            'warehouse_id' => $warehouse1Id,
            'item_id' => $item_standard['id']
        ]);

        $this->assertDatabaseHas('stock', [
            'warehouse_id' => $warehouse1Id,
            'item_id' => $item_variable['id']
        ]);

        $this->assertDatabaseHas('stock', [
            'warehouse_id' => $warehouse1Id,
            'item_id' => $item_service['id']
        ]);

        $this->assertDatabaseHas('stock', [
            'warehouse_id' => $warehouse2Id,
            'item_id' => $item_standard['id']
        ]);

        $this->assertDatabaseHas('stock', [
            'warehouse_id' => $warehouse2Id,
            'item_id' => $item_variable['id']
        ]);

        $this->assertDatabaseHas('stock', [
            'warehouse_id' => $warehouse2Id,
            'item_id' => $item_service['id']
        ]);

        $this->assertDatabaseCount('items', 3);
        $this->assertDatabaseHas('items', [
            'name' => $item_standard['name'],
            'type' => ItemTypesEnum::STANDARD
        ]);

        $this->assertDatabaseHas('items', [
            'name' => $item_variable['name'],
            'type' => ItemTypesEnum::VARIABLE
        ]);

        $this->assertDatabaseHas('items', [
            'name' => $item_service['name'],
            'type' => ItemTypesEnum::SERVICE
        ]);

        $this->assertEquals(1, count($res['data']));
        $this->assertEquals(1, $res['meta']['total']);
    }

    public function test_can_list_stocks_by_warehouse_with_search_term_and_just_return_standard_items()
    {
        $warehouse1Id = $this->createWarehouse()->id;
        $warehouse2Id = $this->createWarehouse()->id;
        $item_standard = $this->createInitItem(warehouse1Id: $warehouse1Id, warehouse2Id: $warehouse2Id);
        $item_variable = $this->createInitItem(ItemTypesEnum::VARIABLE, warehouse1Id: $warehouse1Id, warehouse2Id: $warehouse2Id);
        $item_service = $this->createInitItem(ItemTypesEnum::SERVICE, warehouse1Id: $warehouse1Id, warehouse2Id: $warehouse2Id);

        $res = $this->get(route('api.stock.by_warehouse', [
            'search' => $item_standard['name'],
            'warehouse' => 1,
            'invoice_type' => 'purchase',
            'not_include' => [3]
        ]))->json();

        $this->assertDatabaseCount('stock', 6);
        $this->assertDatabaseCount('items', 3);

        $this->assertEquals(1, count($res['data']));
        $this->assertEquals(1, $res['meta']['total']);

        $this->assertEquals($res['data'][0]['item']['name'], $item_standard['name']);
    }

    public function test_can_list_stocks_by_warehouse_with_search_term_and_not_show_variable_items_when_it_parent_name_it_equal()
    {
        $warehouse1Id = $this->createWarehouse()->id;
        $warehouse2Id = $this->createWarehouse()->id;
        $item_standard = $this->createInitItem(warehouse1Id: $warehouse1Id, warehouse2Id: $warehouse2Id);
        $item_variable = $this->createInitItem(ItemTypesEnum::VARIABLE, warehouse1Id: $warehouse1Id, warehouse2Id: $warehouse2Id);
        $item_service = $this->createInitItem(ItemTypesEnum::SERVICE, warehouse1Id: $warehouse1Id, warehouse2Id: $warehouse2Id);

        $res = $this->get(route('api.stock.by_warehouse', [
            'search' => $item_variable['name'],
            'warehouse' => 1,
            'invoice_type' => 'purchase',
            'not_include' => [3]
        ]))->json();

        $this->assertDatabaseCount('stock', 6);
        $this->assertDatabaseCount('items', 3);

        $this->assertEquals(0, count($res['data']));
        $this->assertEquals(0, $res['meta']['total']);
    }

    public function test_can_list_stocks_by_warehouse_with_search_term_and_just_return_variable_items_variants()
    {
        $warehouse1Id = $this->createWarehouse()->id;
        $warehouse2Id = $this->createWarehouse()->id;
        $item_standard = $this->createInitItem(warehouse1Id: $warehouse1Id, warehouse2Id: $warehouse2Id);
        $item_variable = $this->createInitItem(ItemTypesEnum::VARIABLE, warehouse1Id: $warehouse1Id, warehouse2Id: $warehouse2Id);
        $item_service = $this->createInitItem(ItemTypesEnum::SERVICE, warehouse1Id: $warehouse1Id, warehouse2Id: $warehouse2Id);

        $res = $this->get(route('api.stock.by_warehouse', [
            'search' => $item_variable['variants'][0]['name'],
            'warehouse' => 1,
            'invoice_type' => 'purchase',
            'not_include' => [3]
        ]))->json();
        
        $this->assertDatabaseCount('stock', 6);
        $this->assertDatabaseCount('items', 3);
       
        $this->assertEquals(1, count($res['data']));
        $this->assertEquals(1, $res['meta']['total']);

        $this->assertEquals($res['data'][0]['variant']['name'], $item_variable['variants'][0]['name']);
    }

    public function test_can_not_list_stocks_by_warehouse_with_search_term_when_items_is_services()
    {
        $warehouse1Id = $this->createWarehouse()->id;
        $warehouse2Id = $this->createWarehouse()->id;
        $item_standard = $this->createInitItem(warehouse1Id: $warehouse1Id, warehouse2Id: $warehouse2Id);
        $item_variable = $this->createInitItem(ItemTypesEnum::VARIABLE, warehouse1Id: $warehouse1Id, warehouse2Id: $warehouse2Id);
        $item_service = $this->createInitItem(ItemTypesEnum::SERVICE, warehouse1Id: $warehouse1Id, warehouse2Id: $warehouse2Id);

        $res = $this->get(route('api.stock.by_warehouse', [
            'search' => $item_service['name'],
            'warehouse' => 1,
            'invoice_type' => 'purchase',
            'not_include' => [3]
        ]))->json();
        
        $this->assertDatabaseCount('stock', 6);
        $this->assertDatabaseCount('items', 3);
       
        $this->assertEquals(0, count($res['data']));
        $this->assertEquals(0, $res['meta']['total']);
    }
}
