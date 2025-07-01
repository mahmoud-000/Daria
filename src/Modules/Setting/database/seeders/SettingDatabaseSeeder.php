<?php

namespace Modules\Setting\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Setting\Models\Setting;

class SettingDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Setting::insert(
            [
                [
                    'key' => 'system_logo',
                    'value' => ''
                ],
                [
                    'key' => 'driver',
                    'value' => 'smtp'
                ],
                [
                    'key' => 'host',
                    'value' => 'localhost'
                ],
                [
                    'key' => 'port',
                    'value' => 1025
                ],
                [
                    'key' => 'encryption',
                    'value' => false
                ],
                [
                    'key' => 'username',
                    'value' => 'Test Username'
                ],
                [
                    'key' => 'password',
                    'value' => 'Password@1'
                ],
                [
                    'key' => 'sender_name',
                    'value' => 'Support'
                ],
                [
                    'key' => 'sender_email',
                    'value' => 'support@mail.com'
                ],
                [
                    'key' => 'system_email',
                    'value' => 'system@system.com'
                ],
                [
                    'key' => 'system_name',
                    'value' => 'Daria CRM'
                ],

                [
                    'key' => 'company_email',
                    'value' => 'company@company.com'
                ],
                [
                    'key' => 'company_name',
                    'value' => 'Company Name'
                ],
                
                [
                    'key' => 'default_currency',
                    'value' => 'USD'
                ],
            ]
        );
        // Setting::factory(10)->create();
    }
}
