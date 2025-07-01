<?php

use Modules\Warehouse\Http\Controllers\WarehouseBulkDestroy;
use Modules\Warehouse\Http\Controllers\WarehouseDestroy;
use Modules\Warehouse\Http\Controllers\WarehouseImportCsv;
use Modules\Warehouse\Http\Controllers\WarehouseOptions;
use Modules\Warehouse\Http\Controllers\WarehouseShow;
use Modules\Warehouse\Http\Controllers\WarehousesList;
use Modules\Warehouse\Http\Controllers\WarehouseStore;
use Modules\Warehouse\Http\Controllers\WarehouseUpdate;

Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    Route::get('/warehouses/options', WarehouseOptions::class)->name('warehouses.options');
    Route::get('/warehouses', WarehousesList::class)->name('warehouses.index');
    Route::post('/warehouses', WarehouseStore::class)->name('warehouses.store');
    Route::put('/warehouses/{warehouse}', WarehouseUpdate::class)->name('warehouses.update');
    Route::get('/warehouses/{warehouse}', WarehouseShow::class)->name('warehouses.show');
    Route::delete('/warehouses/{warehouse}', WarehouseDestroy::class)->name('warehouses.destroy');
    Route::post('/warehouses/bulk_destroy', WarehouseBulkDestroy::class)->name('warehouses.bulk_destroy');
    Route::post('/warehouses/import_csv', WarehouseImportCsv::class)->name('warehouses.import_csv');
});
