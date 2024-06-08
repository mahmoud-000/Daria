<?php

namespace Modules\Permission\Database\Seeders;

use Illuminate\Database\Seeder;;
use Modules\Permission\Models\Permission;
use Modules\Role\Models\Role;

class PermissionDatabaseSeeder extends Seeder
{
    public function run()
    {
        $modules = [
            'company' => 'companies',
            'branch' => 'branches',
            'department' => 'departments',
            'user' => 'users',
            'supplier' => 'suppliers',
            'customer' => 'customers',
            'delegate' => 'delegates',
            'shipping company' => 'shipping companies',
            'category' => 'categories',
            'brand' => 'brands',
            'unit' => 'units',
            'warehouse' => 'warehouses',
            'item' => 'items',
            'purchase' => 'purchases',
            'purchase return' => 'purchase returns',
            'sale' => 'sales',
            'sale return' => 'sale returns',
            'quotation' => 'quotations',
            'adjustment' => 'adjustments',
            'transfer' => 'transfers',
            'pipeline' => 'pipelines',
            'sales channel' => 'sales channels',
            'lead' => 'leads',
            'stage' => 'stages',
            'variant' => 'variants',
            'role' => 'roles',
        ];

        $permissions = [
            'List' => 'list-',
            'Create' => 'create-',
            'Edit' => 'edit-',
            'Show' => 'show-',
            'Delete' => 'delete-',
            'Import File' => 'import-file-',
            'Export File' => 'export-file-',
            'Bulk Delete' => 'bulk-delete-'
        ];

        $permissionsArray = [];

        foreach ($modules as $keyMod => $module) {
            foreach ($permissions as $keyPer => $permission) {
                $permissionsArray[] = [
                    'name' =>  $keyPer . ' ' . ucwords($keyMod),
                    'slug' => $permission . $keyMod,
                    'guard_name' => ucwords($keyMod)
                ];
            }
        }

        $permissionsArray[] = [
            'name' =>  'List Settings',
            'slug' => 'list-settings',
            'guard_name' => 'Setting',
        ];

        $permissionsArray[] = [
            'name' =>  'Edit System Settings',
            'slug' => 'edit-system-settings',
            'guard_name' => 'Setting',
        ];

        $permissionsArray[] = [
            'name' =>  'List System Settings',
            'slug' => 'list-system-settings',
            'guard_name' => 'Setting'
        ];

        $permissionsArray[] = [
            'name' =>  'Edit User Settings',
            'slug' => 'edit-user-settings',
            'guard_name' => 'Setting',
        ];
        $permissionsArray[] = [
            'name' =>  'List User Settings',
            'slug' => 'list-user-settings',
            'guard_name' => 'Setting'
        ];

        $permissionsArray[] = [
            'name' =>  'Show User Profile',
            'slug' => 'show-user-profile',
            'guard_name' => 'User',
        ];

        $permissionsArray[] = [
            'name' =>  'Edit User Profile',
            'slug' => 'edit-user-profile',
            'guard_name' => 'User',
        ];

        Permission::insert($permissionsArray);
        $permissions = Permission::all();
        
        $roleAdmin = Role::create([
            'name' => 'Admin',
            'slug' => 'admin',
            'is_active' => true
        ]);
        // $roleCustomer = Role::create([
        //     'name' => 'Customer',
        //     'slug' => 'customer',
        //     'is_active' => true
        // ]);
        $roleAdmin->permissions()->attach($permissions);
        // $per = $permissions->pluck('id', 'slug');
        // dd($per->only(['show-post', 'create-post', 'edit-post']));
        // $roleCustomer->permissions()->attach($per->only(['list-ticket', 'show-ticket', 'create-ticket', 'show-customer-profile', 'edit-customer-profile']));

        // $user = User::where('username', 'admin')->first();
        // dd($user);
        // $user->roles()->attach($roleAdmin);

        // $customer = Customer::where('username', 'customer')->first();
        // dd($customer);
        // $customer->roles()->attach($roleCustomer);
    }
}
