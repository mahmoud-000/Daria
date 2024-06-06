<?php

use Modules\Purchase\Http\Controllers\PurchaseBulkDestroy;
use Modules\Purchase\Http\Controllers\PurchaseDestroy;
use Modules\Purchase\Http\Controllers\PurchaseImportCsv;
use Modules\Purchase\Http\Controllers\PurchaseShow;
use Modules\Purchase\Http\Controllers\PurchasesList;
use Modules\Purchase\Http\Controllers\PurchaseStore;
use Modules\Purchase\Http\Controllers\PurchaseUpdate;
use Modules\Purchase\Http\Controllers\PurchaseFormOptions;

Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    Route::get('/purchases/options', PurchaseFormOptions::class)->name('purchases.options');
    Route::get('/purchases', PurchasesList::class)->name('purchases.index');
    Route::post('/purchases', PurchaseStore::class)->name('purchases.store');
    Route::put('/purchases/{purchase}', PurchaseUpdate::class)->name('purchases.update');
    Route::get('/purchases/{purchase}', PurchaseShow::class)->name('purchases.show');
    Route::delete('/purchases/{purchase}', PurchaseDestroy::class)->name('purchases.destroy');
    Route::post('/purchases/bulk_destroy', PurchaseBulkDestroy::class)->name('purchases.bulk_destroy');
    Route::post('/purchases/import_csv', PurchaseImportCsv::class)->name('purchases.import_csv');
});
