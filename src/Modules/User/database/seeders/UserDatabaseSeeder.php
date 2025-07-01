<?php

namespace Modules\User\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Contact\Models\Contact;
use Modules\Location\Models\Location;
use Modules\Role\Models\Role;
use Modules\User\Models\User;

class UserDatabaseSeeder extends Seeder
{
    public function run()
    {
        $owner = User::create([
            'username'      => 'owner',
            'firstname'     => 'Owner',
            'lastname'      => 'Owner',
            'email'         => 'owner@owner.com',
            'is_active'        => true,
            'is_owner'        => true,
            'send_notify'        => true,
            'password'      => 'Passwordsecret1@',
            'gender'        => 1,
            'remarks'        => 'test remarks',
        ]);

        $admin = User::create([
            'username'      => 'admin',
            'firstname'     => 'Admin',
            'lastname'      => 'Admin',
            'email'         => 'admin@admin.com',
            'is_active'        => true,
            'is_owner'        => false,
            'send_notify'        => true,
            'password'      => 'Passwordsecret1@',
            'gender'        => 1,
            'remarks'        => 'test remarks',
        ]);
        // $permissions = Permission::all();
        // $user->permissions()->attach($permissions);
        $role = Role::where('slug', 'admin')->first();
        $admin->roles()->attach($role);
        User::factory(3)
            ->has(Contact::factory()->count(2))
            ->has(Location::factory()->count(3))
            ->create();
    }
}
