<?php

namespace Tests;

use App\Enums\ItemTypesEnum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Gate;
use Laravel\Sanctum\Sanctum;
use Modules\Adjustment\Models\Adjustment;
use Modules\Brand\Models\Brand;
use Modules\Category\Models\Category;
use Modules\Contact\Models\Contact;
use Modules\Customer\Models\Customer;
use Modules\Delegate\Models\Delegate;
use Modules\Detail\Models\Detail;
use Modules\Location\Models\Location;
use Modules\Payment\Models\Payment;
use Modules\Permission\Models\Permission;
use Modules\Pipeline\Models\Pipeline;
use Modules\Item\Models\Item;
use Modules\Purchase\Models\Purchase;
use Modules\PurchaseReturn\Models\PurchaseReturn;
use Modules\Sale\Models\Sale;
use Modules\SaleReturn\Models\SaleReturn;
use Modules\Role\Models\Role;
use Modules\Setting\Models\Setting;
use Modules\Stage\Models\Stage;
use Modules\Stock\Models\Stock;
use Modules\Supplier\Models\Supplier;
use Modules\Unit\Models\Unit;
use Modules\User\Models\User;
use Modules\Variant\Models\Variant;
use Modules\Warehouse\Models\Warehouse;
use Modules\Company\Models\Company;
use Modules\Branch\Models\Branch;
use Modules\Department\Models\Department;
use Modules\Job\Models\Job;
use Modules\Quotation\Models\Quotation;
use Modules\Region\Models\Region;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    public function setupCompleted()
    {
        return Setting::create(
            [
                'key' => 'system_setup_completed',
                'value' => 1
            ]
        );
    }

    public function createRegion($args = [])
    {
        return Region::factory()->create($args);
    }

    public function createJob($args = [])
    {
        return Job::factory()->create($args);
    }

    public function createCompany($args = [])
    {
        return Company::factory()->create($args);
    }

    public function createBranch($args = [])
    {
        return Branch::factory()->make($args);
    }

    public function createDepartment($args = [])
    {
        return Department::factory()->create($args);
    }

    public function storeBranch($args = [])
    {
        return Branch::factory()->create($args);
    }

    public function createUser($args = [])
    {
        return User::factory()->create($args);
    }

    public function createCustomer($args = [])
    {
        return Customer::factory()->create($args);
    }

    public function createSupplier($args = [])
    {
        return Supplier::factory()->create($args);
    }

    public function createDelegate($args = [])
    {
        return Delegate::factory()->create($args);
    }

    public function createPipeline($args = [])
    {
        return Pipeline::factory()
            ->has(Stage::factory()->state(['name' => 'New', 'complete' => 0, 'is_default' => true, 'is_active' => true])->count(1))
            ->has(Stage::factory()->state(['name' => 'Complete', 'complete' => 100, 'is_default' => true, 'is_active' => true])->count(1))
            ->create($args);
    }

    public function createStage($args = [])
    {
        return Stage::factory()->make($args);
    }

    public function storeStage($args = [])
    {
        return Stage::factory()->create($args);
    }

    public function createPurchase($args = [])
    {
        return Purchase::factory()->create($args);
    }

    public function createSale($args = [])
    {
        return Sale::factory()->create($args);
    }

    public function createPurchaseReturn($args = [])
    {
        return PurchaseReturn::factory()->create($args);
    }

    public function createSaleReturn($args = [])
    {
        return SaleReturn::factory()->create($args);
    }

    public function createQuotation($args = [])
    {
        return Quotation::factory()->create($args);
    }

   public function createAdjustment($args = [])
   {
       return Adjustment::factory()->create($args);
   } 

    public function createDetail($args = [])
    {
        return Detail::factory()->make($args);
    }

    public function createPayment($args = [])
    {
        return Payment::factory()->make($args);
    }

    public function createVariant($args = [])
    {
        return Variant::factory()->make($args);
    }

    public function createContact($args = [])
    {
        return Contact::factory()->create($args);
    }

    public function createLocation($args = [])
    {
        return Location::factory()->create($args);
    }

    public function createSetting($args = [])
    {
        return Setting::factory()->create($args);
    }
    public function createCategory($args = [])
    {
        return Category::factory()->create($args);
    }


    public function createBrand($args = [])
    {
        return Brand::factory()->create($args);
    }

    public function createUnit($args = [])
    {
        return Unit::factory()->create($args);
    }

    public function createWarehouse($args = [])
    {
        return Warehouse::factory()->create($args);
    }

    public function createStock($args = [])
    {
        return Stock::factory()->create($args);
    }

    public function createItem($args = [])
    {
        return Item::factory()->create($args);
    }

    public function createInitItem($type = ItemTypesEnum::STANDARD, $unit = 'kg', $cost = 10, $price = 20, $tax = 10, $taxType = 1, $productType = 1, $warehouse1Id = null, $warehouse2Id = null, $isActiveVariant = true)
    {
        $warehouse1Id = $warehouse1Id ?? $this->createWarehouse()->id;
        $warehouse2Id = $warehouse2Id ?? $this->createWarehouse()->id;
        $unitKg = $this->createUnit(['name' => 'Kilogram', 'short_name' => 'kg', 'operator' => '*', 'operator_value' => 1]);
        $unitG = $this->createUnit(['name' => 'Gram', 'short_name' => 'g', 'operator' => '/', 'operator_value' => 1000, 'unit_id' => $unitKg->id]);

        $item = $this->createItem([
            'type' => $type,
            'product_type' => $productType,
            'unit_id' => $unitKg->id,
            'purchase_unit_id' => $unit === 'kg' ? $unitKg->id : $unitG->id,
            'sale_unit_id' => $unit === 'kg' ? $unitKg->id : $unitG->id,
            'cost' => $type === ItemTypesEnum::STANDARD ? $cost : 0,
            'price' => $type !== ItemTypesEnum::VARIABLE ? $price : 0,
            'tax' => $tax,
            'tax_type' => $taxType,
            'is_available_for_purchase' => $type === ItemTypesEnum::SERVICE ? false : true, 
            'is_available_for_sale' => true, 
        ]);

        if ($type === ItemTypesEnum::VARIABLE) {
            $item->variants()->create($this->createVariant(['cost' => $cost, 'price' => $price, 'is_active' => $isActiveVariant])->toArray());
        }

        // Warehouse 1
        $this->createStock([
            'warehouse_id' => $warehouse1Id,
            'item_id' => $item->id,
            'variant_id' => $type === ItemTypesEnum::VARIABLE ? $item->variants->first()->id : null,
            'quantity' => 0
        ]);

        // Warehouse 2
        $this->createStock([
            'warehouse_id' => $warehouse2Id,
            'item_id' => $item->id,
            'variant_id' => $type === ItemTypesEnum::VARIABLE ? $item->variants->first()->id : null,
            'quantity' => 0
        ]);

        return $item;
    }

    public function createOwner()
    {
        $owner = $this->createUser(['username' => 'owner', 'is_owner' => true, 'is_active' => true]);
        Sanctum::actingAs($owner);
        $this->mockPermissionsWithGates($owner);
        $permissions = Permission::all();
        $owner->permissions()->attach($permissions);
        return $owner;
    }

    public function mockPermissionsWithGates($user)
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
            'purchaseReturn' => 'purchaseReturns',
            'sale' => 'sales',
            'saleReturn' => 'saleReturns',
            'quotation' => 'quotations',
            'adjustment' => 'adjustments',
            'transfer' => 'transfers',
            'pipeline' => 'pipelines',
            'channel' => 'channels',
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
            'slug' => 'admin'
        ]);
        $roleSales = Role::create([
            'name' => 'Sales',
            'slug' => 'sales'
        ]);
        $roleAdmin->permissions()->attach($permissions);
        $user->roles()->attach($roleAdmin);
        foreach ($permissions as $permission) {
            Gate::define($permission->slug, function ($user) use ($permission) {
                return $user->hasPermissionsTo($permission->slug);
            });
        }
    }
}
