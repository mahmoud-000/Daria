<?php

namespace Database\Seeders;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Modules\Brand\Database\Seeders\BrandDatabaseSeeder;
use Modules\Category\Database\Seeders\CategoryDatabaseSeeder;
use Modules\Supplier\Database\Seeders\SupplierDatabaseSeeder;
use Modules\Permission\Database\Seeders\PermissionDatabaseSeeder;
use Modules\Pipeline\Database\Seeders\PipelineDatabaseSeeder;
use Modules\Item\Database\Seeders\ItemDatabaseSeeder;
use Modules\Setting\Database\Seeders\SettingDatabaseSeeder;
use Modules\Setup\Database\Seeders\SetupDatabaseSeeder;
use Modules\Unit\Database\Seeders\UnitDatabaseSeeder;
use Modules\User\Database\Seeders\UserDatabaseSeeder;
use Modules\Delegate\Database\Seeders\DelegateDatabaseSeeder;
use Modules\Warehouse\Database\Seeders\WarehouseDatabaseSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            SettingDatabaseSeeder::class,
            PermissionDatabaseSeeder::class,
            SetupDatabaseSeeder::class,
            WarehouseDatabaseSeeder::class,
            CategoryDatabaseSeeder::class,
            BrandDatabaseSeeder::class,
            UnitDatabaseSeeder::class,
            // ItemDatabaseSeeder::class,
            SupplierDatabaseSeeder::class,
            DelegateDatabaseSeeder::class,
            UserDatabaseSeeder::class,
            PipelineDatabaseSeeder::class,
        ]);
    }
}
