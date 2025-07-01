<?php

use Modules\Supplier\Http\Controllers\SupplierBulkDestroy;
use Modules\Supplier\Http\Controllers\SupplierDestroy;
use Modules\Supplier\Http\Controllers\SupplierImportCsv;
use Modules\Supplier\Http\Controllers\SupplierOptions;
use Modules\Supplier\Http\Controllers\SupplierRegister;
use Modules\Supplier\Http\Controllers\SupplierShow;
use Modules\Supplier\Http\Controllers\SuppliersList;
use Modules\Supplier\Http\Controllers\SupplierStore;
use Modules\Supplier\Http\Controllers\SupplierUpdate;

Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    Route::get('/suppliers/options', SupplierOptions::class)->name('suppliers.options');
    Route::get('/suppliers', SuppliersList::class)->name('suppliers.index');
    Route::post('/suppliers', SupplierStore::class)->name('suppliers.store');
    Route::put('/suppliers/{supplier}', SupplierUpdate::class)->name('suppliers.update');
    Route::get('/suppliers/{supplier}', SupplierShow::class)->name('suppliers.show');
    Route::delete('/suppliers/{supplier}', SupplierDestroy::class)->name('suppliers.destroy');
    Route::post('/suppliers/bulk_destroy', SupplierBulkDestroy::class)->name('suppliers.bulk_destroy');
    Route::post('/suppliers/import_csv', SupplierImportCsv::class)->name('suppliers.import_csv');
});

Route::middleware('guest')->prefix('v1')->group(function () {
    Route::post('/suppliers/register', SupplierRegister::class)->name('suppliers.register');
});
