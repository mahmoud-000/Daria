<?php

namespace Modules\Setting\Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;


class UserSettingTest extends TestCase
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

    public function test_can_set_user_locale()
    {
        $res = $this->post(route('api.locale.setLocale'), [
            'locale' => 'en-US'
        ])->json();

        $this->assertDatabaseHas('settings', [
            'key' => 'locale',
            'value' => 'en',
            'user_id' => auth()->id()
        ]);
        
        $this->assertEquals($res['locale'], 'en');
        
    }

    public function test_can_get_user_settings_by_id()
    {
        $user = $this->createUser();
        $setting = $this->createSetting(
            [
                'user_id' => $user->id
            ]
        );
        $res = $this->get(route('api.settings.user', $user->id))->json();
        $this->assertEquals($res['payload']['user_id'], $user->id);
        $this->assertEquals($res['payload']['user_id'], $setting['user_id']);
        $this->assertEquals($res['payload']['key'], $setting['key']);
        $this->assertEquals($res['payload']['value'], $setting['value']);
    }

    public function test_can_store_user_settings_with_correct_data()
    {
        $this->post(
            route('api.settings.user_update', []),
            [
                [
                    'key' => 'new.user.key',
                    'value' => 'new.user.value'
                ]
            ]
        );

        $this->assertDatabaseCount('settings', 2);
        $this->assertDatabaseHas('settings', [
            'key' => 'new.user.key',
            'value' => 'new.user.value',
            'user_id' => auth()->id()
        ]);
    }

    public function test_can_update_user_settings_with_correct_data()
    {
        $setting = $this->createSetting(
            [
                'key' => 'old.key',
                'value' => 'old.value',
                'user_id' => auth()->id()
            ]
        );
        $res = $this->post(
            route('api.settings.user_update'),
            [
                [
                    'key' => 'old.key',
                    'value' => 'new.user.value',
                ]
            ]
        )->json();

        $this->assertDatabaseCount('settings', 2);
        $this->assertDatabaseHas('settings', [
            'key' => 'old.key',
            'value' => 'new.user.value',
            'user_id' => auth()->id()
        ]);

        $this->assertEquals('old.key', $res['payload'][0]['key']);
        $this->assertEquals('new.user.value', $res['payload'][0]['value']);
        $this->assertEquals(auth()->id(), $res['payload'][0]['user_id']);
    }
}
