<?php

namespace Modules\Setting\Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SystemSettingTest extends TestCase
{
    use RefreshDatabase;
    public $settings;
    public function setup(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
        $this->settings = $this->setupCompleted();
        $this->createOwner();
    }

    public function test_can_list_system_settings_only()
    {
        $res = $this->get(route('api.settings.system'))->json();
        $this->assertEquals(null, $res['data'][0]['user_id']);
        $this->assertEquals($this->settings['key'], $res['data'][0]['key']);
        $this->assertEquals($this->settings['id'], $res['data'][0]['id']);
    }

    public function test_can_not_store_system_settings_without_correct_data()
    {
        $this->post(route('api.settings.system_update'), [[]])
            ->assertJsonMissingValidationErrors([
                '0.key' => 'The key field is required.',
            ])->assertStatus(422);
    }

    public function test_can_store_system_settings_with_correct_data_and_remove_user_id()
    {
        $this->post(
            route('api.settings.system_update', []),
            [
                [
                    'key' => 'new.key',
                    'value' => 'new.value',
                    'user_id' => 1
                ]
            ]
        );

        $this->assertDatabaseCount('settings', 2);
        $this->assertDatabaseHas('settings', [
            'key' => 'new.key',
            'value' => 'new.value',
            'user_id' => null
        ]);
    }

    public function test_can_store_system_settings_with_correct_data()
    {
        $this->post(
            route('api.settings.system_update'),
            [
                [
                    'key' => 'new.key',
                    'value' => 'new.value'
                ]
            ]
        );
        $this->assertDatabaseHas('settings', [
            'key' => 'new.key',
            'value' => 'new.value',
            'user_id' => null
        ]);
    }

    public function test_can_update_system_settings()
    {
        $setting = $this->createSetting(
            [
                'key' => 'old.key',
                'value' => 'old.value',
                'user_id' => null
            ]
        );
        $res = $this->post(
            route('api.settings.system_update'),
            [
                [
                    'key' => 'old.key',
                    'value' => 'new.value',
                ]
            ]
        )->json();

        $this->assertDatabaseCount('settings', 2);
        $this->assertDatabaseHas('settings', [
            'key' => 'old.key',
            'value' => 'new.value',
            'user_id' => null
        ]);

        $this->assertEquals('old.key', $setting['key']);
    }
}
