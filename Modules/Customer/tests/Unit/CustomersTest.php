<?php

namespace Modules\Customer\Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CustomersTest extends TestCase
{
    use RefreshDatabase;
    public $customer;
    public function setup(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
        $this->setupCompleted();
        $this->customer = $this->createCustomer();
        $this->createOwner();
    }

    public function test_can_list_customers()
    {
        $res = $this->get(route('api.customers.index'))->json();
        $this->assertEquals(1, count($res['data']));
        $this->assertEquals(1, $res['meta']['total']);
    }

    public function test_can_create_customer_with_required_inputs()
    {
        $res = $this->post(route('api.customers.store'), [
            'fullname' => 'testcustomername',
            'type' => 2,
            'company_name' => 'testcompanyname',
            'password' => 'Password1@',
            'password_confirmation' => 'Password1@'
        ])->json();

        $this->assertDatabaseCount('customers', 2);
        $this->assertDatabaseHas('customers', [
            'fullname' => 'testcustomername',
            'company_name' => 'testcompanyname'
        ]);
        $this->assertTrue($res['success']);
        $this->assertEquals($res['payload'], __('status.created', ['name' => 'testcustomername', 'module' => __('modules.customer')]));
    }

    public function test_can_edit_customer()
    {
        $res = $this->put(
            route('api.customers.update', ['customer' => $this->customer]),
            [
                'fullname' => 'newcustomername',
                'type' => 2,
                'company_name' => 'newcompanyname',
                'password' => 'Password1@',
                'password_confirmation' => 'Password1@'
            ]
        )->json();

        $this->assertDatabaseCount('customers', 1);
        $this->assertDatabaseHas('customers', [
            'fullname' => 'newcustomername',
            'company_name' => 'newcompanyname'
        ]);
        $this->assertTrue($res['success']);
        $this->assertEquals($res['payload'], __('status.updated', ['name' => 'newcustomername', 'module' => __('modules.customer')]));
    }

    public function test_can_show_customer()
    {
        $customerId = $this->customer->id;
        $res = $this->get(route('api.customers.show', ['customer' => $customerId]))->json();
        $this->assertEquals(1, count($res));
        $this->assertEquals($customerId, $res['data']['id']);
    }

    public function test_can_show_customer_with_contacts()
    {
        $customer = $this->customer;
        $this->createContact([
            'contactable_id' => $customer->id,
            'contactable_type' => 'Customer',
        ]);
        $res = $this->get(route('api.customers.show', ['customer' => $customer->id]))->json();

        $this->assertEquals(1, count($res));
        $this->assertEquals($customer->id, $res['data']['id']);
        $this->assertEquals($customer->id, $res['data']['contacts'][0]['contactable_id']);
    }

    public function test_can_show_customer_with_locations()
    {
        $customer = $this->customer;
        $this->createLocation([
            'locationable_id' => $customer->id,
            'locationable_type' => 'Customer',
        ]);
        $res = $this->get(route('api.customers.show', ['customer' => $customer->id]))->json();

        $this->assertEquals(1, count($res));
        $this->assertEquals($customer->id, $res['data']['id']);
        $this->assertEquals($customer->id, $res['data']['locations'][0]['locationable_id']);
    }

    public function test_can_delete_customer()
    {
        $res = $this->delete(route('api.customers.destroy', ['customer' => $this->customer->id]))->json();
        $this->assertTrue($res['success']);
    }
}
